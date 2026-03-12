<?php

namespace App\Filament\Resources\Operators\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OperatorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('Nome')
                    ->required(),
                TextInput::make('cpf')
                    ->required(),
                TextInput::make('Telefone')
                    ->tel(),
                TextInput::make('Numero da carteira'),
                TextInput::make('Categoria da Carteira'),
                DatePicker::make('license_expires_at'),
                Toggle::make('active')
                    ->required(),
                Textarea::make('Observação')
                    ->columnSpanFull(),
            ]);
    }
}
