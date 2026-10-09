<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanFineResource\Pages;
use App\Models\LoanFine;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LoanFineResource extends Resource
{
    protected static ?string $model = LoanFine::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Denda';
    protected static ?string $pluralModelLabel = 'Denda Peminjaman';
    protected static ?string $modelLabel = 'Denda';
    protected static ?string $navigationGroup = 'Sirkulasi';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('book_loan_id')
                    ->relationship('loan', 'loan_code')
                    ->required()
                    ->label('Kode Peminjaman'),
                Forms\Components\TextInput::make('overdue_days')
                    ->required()
                    ->numeric()
                    ->label('Terlambat (hari)'),
                Forms\Components\TextInput::make('fine_per_day')
                    ->required()
                    ->numeric()
                    ->label('Denda Per Hari'),
                Forms\Components\TextInput::make('total_fine')
                    ->required()
                    ->numeric()
                    ->label('Total Denda'),
                Forms\Components\Select::make('payment_status')
                    ->options([
                        'belum_bayar' => 'Belum Bayar',
                        'sudah_bayar' => 'Sudah Bayar',
                    ])
                    ->required()
                    ->label('Status Pembayaran'),
                Forms\Components\DateTimePicker::make('paid_at')
                    ->label('Waktu Bayar'),
                Forms\Components\Select::make('paid_by')
                    ->relationship('paidByUser', 'name')
                    ->label('Petugas Pendaftar'),
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('loan.loan_code')
                    ->label('Kode Pinjam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_fine')
                    ->label('Total Denda')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'belum_bayar' => 'danger',
                        'sudah_bayar' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->label('Waktu Bayar')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('paidByUser.name')
                    ->label('Petugas')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoanFines::route('/'),
            'create' => Pages\CreateLoanFine::route('/create'),
            'edit' => Pages\EditLoanFine::route('/{record}/edit'),
        ];
    }
}
