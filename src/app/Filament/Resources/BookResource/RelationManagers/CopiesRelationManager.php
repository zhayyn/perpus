<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CopiesRelationManager extends RelationManager
{
    protected static string $relationship = 'copies';
    protected static ?string $title = 'Eksemplar Buku (Copy)';
    protected static ?string $modelLabel = 'Eksemplar';
    protected static ?string $pluralModelLabel = 'Eksemplar Buku';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('copy_code')
                    ->label('Kode Copy / Barcode')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->default(fn () => 'CPY-' . strtoupper(uniqid())),
                    
                Forms\Components\Select::make('condition')
                    ->label('Kondisi')
                    ->options([
                        'baik' => 'Baik',
                        'rusak_ringan' => 'Rusak Ringan',
                        'rusak_berat' => 'Rusak Berat',
                        'hilang' => 'Hilang',
                    ])
                    ->required()
                    ->default('baik'),
                    
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'dipinjam' => 'Dipinjam',
                        'reservasi' => 'Reservasi',
                        'perbaikan' => 'Dalam Perbaikan',
                    ])
                    ->required()
                    ->default('tersedia')
                    // Jangan biarkan user mengubah status jika sedang dipinjam
                    ->disabled(fn ($record) => $record && $record->status === 'dipinjam'),
                    
                Forms\Components\DatePicker::make('acquired_at')
                    ->label('Tanggal Masuk')
                    ->default(now()),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan Tambahan')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('copy_code')
            ->columns([
                Tables\Columns\TextColumn::make('copy_code')
                    ->label('Kode Copy')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                    
                Tables\Columns\BadgeColumn::make('condition')
                    ->label('Kondisi')
                    ->colors([
                        'success' => 'baik',
                        'warning' => 'rusak_ringan',
                        'danger' => 'rusak_berat',
                        'secondary' => 'hilang',
                    ]),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'tersedia',
                        'warning' => 'dipinjam',
                        'secondary' => 'reservasi',
                        'danger' => 'perbaikan',
                    ]),
                    
                Tables\Columns\TextColumn::make('acquired_at')
                    ->label('Tanggal Masuk')
                    ->date('d M Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'dipinjam' => 'Dipinjam',
                        'reservasi' => 'Reservasi',
                        'perbaikan' => 'Dalam Perbaikan',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Eksemplar'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => $record->status !== 'dipinjam'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
