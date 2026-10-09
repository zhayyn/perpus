<?php

namespace App\Filament\Pages;

use App\Models\LibrarySetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class LibrarySettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Pengaturan Perpustakaan';
    protected static ?string $title           = 'Pengaturan Perpustakaan';
    protected static ?int    $navigationSort  = 99;

    protected static string $view = 'filament.pages.library-settings-page';

    // State form
    public ?array $data = [];

    public function mount(): void
    {
        // Load semua setting dari database ke dalam form
        $this->form->fill([
            'library_name'    => LibrarySetting::get('library_name'),
            'library_address' => LibrarySetting::get('library_address'),
            'fine_per_day'    => LibrarySetting::get('fine_per_day', 1000),
            'max_loan_days'   => LibrarySetting::get('max_loan_days', 7),
            'max_loan_books'  => LibrarySetting::get('max_loan_books', 3),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Perpustakaan')
                    ->description('Data umum yang tampil di header dan laporan')
                    ->icon('heroicon-o-building-library')
                    ->schema([
                        Forms\Components\TextInput::make('library_name')
                            ->label('Nama Perpustakaan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('library_address')
                            ->label('Alamat Perpustakaan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Aturan Peminjaman')
                    ->description('Konfigurasi regulasi peminjaman buku')
                    ->icon('heroicon-o-book-open')
                    ->schema([
                        Forms\Components\TextInput::make('fine_per_day')
                            ->label('Denda Keterlambatan per Hari')
                            ->helperText('Nominal denda dalam Rupiah yang dikenakan setiap hari keterlambatan')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->required()
                            ->extraInputAttributes(['style' => 'font-weight: bold; color: #dc2626']),

                        Forms\Components\TextInput::make('max_loan_days')
                            ->label('Maksimal Hari Pinjam')
                            ->helperText('Jumlah hari maksimal peminjaman sebelum dianggap terlambat')
                            ->numeric()
                            ->suffix('hari')
                            ->minValue(1)
                            ->maxValue(90)
                            ->required(),

                        Forms\Components\TextInput::make('max_loan_books')
                            ->label('Maksimal Buku per Anggota')
                            ->helperText('Berapa buku yang boleh dipinjam sekaligus oleh satu anggota')
                            ->numeric()
                            ->suffix('buku')
                            ->minValue(1)
                            ->maxValue(20)
                            ->required(),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Simpan setiap setting ke database
        foreach ($data as $key => $value) {
            LibrarySetting::set($key, $value);
        }

        Notification::make()
            ->success()
            ->title('Pengaturan Berhasil Disimpan')
            ->body('Semua perubahan sudah aktif.')
            ->send();
    }
}
