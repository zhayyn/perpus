<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookLoanResource\Pages;
use App\Models\BookLoan;
use App\Models\LoanFine;
use App\Models\LibrarySetting;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookLoanResource extends Resource
{
    protected static ?string $model = BookLoan::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Peminjaman & Denda';
    protected static ?string $modelLabel = 'Peminjaman';
    protected static ?string $pluralModelLabel = 'Data Peminjaman';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Peminjaman')
                    ->schema([
                        Forms\Components\Select::make('member_id')
                            ->relationship('member', 'name')
                            ->label('Anggota')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (string $operation): bool => $operation === 'edit')
                            ->rules([
                                fn (string $operation) => function (string $attribute, $value, \Closure $fail) use ($operation) {
                                    if ($operation === 'edit') return;
                                    
                                    // 1. Cek Denda Belum Dibayar
                                    $unpaidFines = \App\Models\LoanFine::whereHas('bookLoan', function($q) use ($value) {
                                        $q->where('member_id', $value);
                                    })->where('payment_status', 'belum_bayar')->count();
                                    
                                    if ($unpaidFines > 0) {
                                        $fail('Anggota ini tidak bisa meminjam karena memiliki denda yang belum dibayar.');
                                    }

                                    // 2. Cek Limit Peminjaman
                                    $activeLoans = \App\Models\BookLoan::where('member_id', $value)
                                        ->whereIn('status', ['dipinjam', 'terlambat'])
                                        ->count();
                                        
                                    $maxLoans = \App\Models\LibrarySetting::get('max_loan_books', 3);
                                    if ($activeLoans >= $maxLoans) {
                                        $fail("Anggota ini telah mencapai batas maksimal peminjaman ({$maxLoans} buku).");
                                    }
                                },
                            ]),

                        Forms\Components\Select::make('book_copy_id')
                            ->relationship('bookCopy', 'copy_code', fn ($query) => $query->where('status', 'tersedia'))
                            ->label('Kode Copy / Copy Buku')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (string $operation): bool => $operation === 'edit')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->copy_code} - {$record->book->title}"),

                        Forms\Components\DatePicker::make('loan_date')
                            ->label('Tanggal Pinjam')
                            ->required()
                            ->default(now())
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),

                        Forms\Components\DatePicker::make('due_date')
                            ->label('Tenggat Waktu')
                            ->required()
                            ->default(now()->addDays(7)),

                        Forms\Components\Select::make('status')
                            ->options([
                                'dipinjam'     => 'Sedang Dipinjam',
                                'dikembalikan' => 'Dikembalikan',
                                'terlambat'    => 'Terlambat',
                                'hilang'       => 'Hilang',
                            ])
                            ->required()
                            ->default('dipinjam'),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ])->columns(2),

                // Section ini hanya tampil saat EDIT dan sudah dikembalikan
                Forms\Components\Section::make('Info Pengembalian')
                    ->schema([
                        Forms\Components\DatePicker::make('return_date')
                            ->label('Tanggal Kembali')
                            ->native(false),

                        Forms\Components\Placeholder::make('denda_info')
                            ->label('Denda Terhitung')
                            ->content(function ($record) {
                                if (!$record || !$record->return_date) return 'Belum dikembalikan';
                                $days = $record->overdue_days;
                                $fine = $days * 1000;
                                return $days > 0
                                    ? "{$days} hari terlambat = Rp " . number_format($fine, 0, ',', '.')
                                    : 'Tepat waktu, tidak ada denda';
                            }),
                    ])
                    ->columns(2)
                    ->hidden(fn (string $operation): bool => $operation === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('loan_code')
                    ->label('Kode')
                    ->searchable()
                    ->copyable()
                    ->color('primary')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('member.name')
                    ->label('Peminjam')
                    ->searchable()
                    ->sortable()
                    ->description(fn (BookLoan $record): string => $record->member?->member_code ?? ''),

                Tables\Columns\TextColumn::make('bookCopy.book.title')
                    ->label('Buku')
                    ->searchable()
                    ->description(fn (BookLoan $record): string => "Kode Copy: " . ($record->bookCopy?->copy_code ?? '-')),

                Tables\Columns\TextColumn::make('loan_date')
                    ->label('Pinjam')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Tenggat')
                    ->date('d M Y')
                    ->color(fn (BookLoan $record): string =>
                        ($record->due_date < today() && in_array($record->status, ['dipinjam', 'terlambat']))
                            ? 'danger' : 'gray'
                    ),

                Tables\Columns\TextColumn::make('return_date')
                    ->label('Dikembalikan')
                    ->date('d M Y')
                    ->placeholder('Belum kembali')
                    ->color('success'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning'   => 'dipinjam',
                        'success'   => 'dikembalikan',
                        'danger'    => 'terlambat',
                        'secondary' => 'hilang',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'dipinjam'     => 'Sedang Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                        'terlambat'    => 'Terlambat',
                        'hilang'       => 'Hilang',
                    ]),
            ])
            ->actions([
                // Tombol utama: Kembalikan Buku (dengan kalkulasi denda otomatis)
                Tables\Actions\Action::make('kembalikan')
                    ->label('Kembalikan')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (BookLoan $record) => in_array($record->status, ['dipinjam', 'terlambat']))
                    ->form([
                        Forms\Components\DatePicker::make('return_date')
                            ->label('Tanggal Dikembalikan')
                            ->required()
                            ->default(now())
                            ->native(false),

                        Forms\Components\Placeholder::make('info_denda')
                            ->label('Catatan')
                            ->content('Denda Rp 1.000/hari untuk keterlambatan. Akan dihitung otomatis.'),
                    ])
                    ->action(function (BookLoan $record, array $data): void {
                        $returnDate  = Carbon::parse($data['return_date']);
                        $overdueDays = $returnDate->gt($record->due_date)
                            ? $record->due_date->diffInDays($returnDate)
                            : 0;
                        $fineAmount  = $overdueDays * LibrarySetting::get('fine_per_day', 1000);

                        // Update status peminjaman
                        $record->update([
                            'return_date' => $returnDate,
                            'status'      => 'dikembalikan',
                        ]);

                        // Kembalikan status copy buku menjadi tersedia
                        $record->bookCopy?->update(['status' => 'tersedia']);

                        // Buat catatan denda jika ada
                        if ($overdueDays > 0) {
                            LoanFine::updateOrCreate(
                                ['book_loan_id' => $record->id],
                                [
                                    'fine_per_day'    => LibrarySetting::get('fine_per_day', 1000),
                                    'overdue_days'    => $overdueDays,
                                    'total_fine'      => $fineAmount,
                                    'payment_status'  => 'belum_bayar',
                                ]
                            );

                            Notification::make()
                                ->warning()
                                ->title('Buku Dikembalikan — Ada Denda!')
                                ->body("{$overdueDays} hari terlambat. Denda: Rp " . number_format($fineAmount, 0, ',', '.'))
                                ->duration(8000)
                                ->send();
                        } else {
                            Notification::make()
                                ->success()
                                ->title('Buku Berhasil Dikembalikan')
                                ->body('Tepat waktu! Tidak ada denda.')
                                ->duration(5000)
                                ->send();
                        }
                    })
                    ->modalHeading('Proses Pengembalian Buku')
                    ->modalDescription('Konfirmasi tanggal pengembalian. Denda akan dihitung otomatis jika terlambat.')
                    ->modalSubmitActionLabel('Konfirmasi Pengembalian'),

                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square'),

                Tables\Actions\Action::make('bayar_denda')
                    ->label('Bayar Denda')
                    ->icon('heroicon-m-banknotes')
                    ->color('warning')
                    ->visible(fn (BookLoan $record) =>
                        $record->status === 'dikembalikan' &&
                        $record->fine?->payment_status === 'belum_bayar'
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pembayaran Denda')
                    ->modalDescription(fn (BookLoan $record) =>
                        "Total denda: Rp " . number_format($record->fine?->total_fine ?? 0, 0, ',', '.') .
                        ". Tandai sebagai lunas?"
                    )
                    ->modalSubmitActionLabel('Tandai Lunas')
                    ->action(function (BookLoan $record): void {
                        $record->fine?->update(['payment_status' => 'sudah_bayar', 'paid_at' => now()->toDateTimeString()]);

                        Notification::make()
                            ->success()
                            ->title('Denda Lunas!')
                            ->body("Denda Rp " . number_format($record->fine?->total_fine ?? 0, 0, ',', '.') . " berhasil dibayar.")
                            ->duration(5000)
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBookLoans::route('/'),
            'create' => Pages\CreateBookLoan::route('/create'),
            'edit'   => Pages\EditBookLoan::route('/{record}/edit'),
        ];
    }
}
