<?php

namespace App\Filament\Resources\Operators\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class OperatorsTable
{

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->icon('heroicon-m-user')
                    ->iconColor('primary')
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('CPF copiado!')
                    ->color('gray'),

                TextColumn::make('phone')
                    ->label('Telefone')
                    ->icon('heroicon-m-phone')
                    ->iconColor('gray')
                    ->placeholder('—'),

                TextColumn::make('license_category')
                    ->label('CNH')
                    ->badge()
                    ->color('success'),

                TextColumn::make('license_expires_at')
                    ->label('Vencimento CNH')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record?->license_expires_at?->isPast() ? 'danger' : 'gray')
                    ->icon('heroicon-m-calendar-days')
                    ->placeholder('—'),

                IconColumn::make('active')
                    ->label('Ativo')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->defaultSort('name')
            ->striped()
            ->filters([
                TernaryFilter::make('active')
                    ->label('Status')
                    ->placeholder('Todos')
                    ->trueLabel('Apenas ativos')
                    ->falseLabel('Apenas inativos'),
            ])
            ->actions([
                EditAction::make()->label('Editar')->icon('heroicon-m-pencil'),
                DeleteAction::make()->label('Excluir')->icon('heroicon-m-trash'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Excluir selecionados'),
                ]),
            ]);
    }
}
