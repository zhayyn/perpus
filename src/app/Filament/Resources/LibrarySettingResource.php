<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LibrarySettingResource\Pages;
use App\Models\LibrarySetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LibrarySettingResource extends Resource
{
    protected static ?string $model = LibrarySetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?string $pluralModelLabel = 'Pengaturan Perpustakaan';
    protected static ?string $modelLabel = 'Pengaturan';
    protected static ?string $navigationGroup = 'Sistem';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255)
                    ->label('Kunci (Key)')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\TextInput::make('label')
                    ->required()
                    ->maxLength(255)
                    ->label('Label Tampilan')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Select::make('type')
                    ->options([
                        'string' => 'Teks (String)',
                        'integer' => 'Angka (Integer)',
                        'boolean' => 'Pilihan (Boolean)',
                        'image' => 'Gambar/Logo (Image)',
                    ])
                    ->required()
                    ->label('Tipe Data')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\TextInput::make('value')
                    ->required(fn (Forms\Get $get) => in_array($get('type'), ['string', 'integer']))
                    ->maxLength(255)
                    ->label('Nilai Pengaturan')
                    ->visible(fn (Forms\Get $get) => in_array($get('type'), ['string', 'integer'])),
                Forms\Components\Select::make('value')
                    ->options(['1' => 'Aktif', '0' => 'Tidak Aktif'])
                    ->required(fn (Forms\Get $get) => $get('type') === 'boolean')
                    ->label('Nilai Pengaturan')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'boolean'),
                Forms\Components\FileUpload::make('value')
                    ->image()
                    ->directory('settings')
                    ->label('Upload Gambar')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'image'),
                Forms\Components\TextInput::make('group')
                    ->maxLength(255)
                    ->label('Grup')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull()
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->searchable()
                    ->label('Pengaturan'),
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->label('Key')
                    ->color('gray'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->limit(50),
                Tables\Columns\TextColumn::make('group')
                    ->label('Grup')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLibrarySettings::route('/'),
            'edit' => Pages\EditLibrarySetting::route('/{record}/edit'),
        ];
    }
}
