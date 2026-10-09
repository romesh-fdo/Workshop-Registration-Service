<?php

namespace App\Filament\Resources\Registrations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workshop_id')
                    ->label('Workshop')
                    ->relationship('workshop', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('attendee_name')
                    ->label('Attendee Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('attendee_email')
                    ->label('Attendee Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
            ]);
    }
}