<?php

namespace App\Filament\Resources\Workshops\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkshopForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Workshop Code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                TextInput::make('title')
                    ->label('Workshop Title')
                    ->required()
                    ->maxLength(255),

                TextInput::make('instructor')
                    ->required()
                    ->maxLength(255),

                DateTimePicker::make('starts_at')
                    ->label('Date & Time')
                    ->required(),

                TextInput::make('capacity')
                    ->numeric()
                    ->integer()
                    ->minValue(1)
                    ->required(),

                Select::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ])
                    ->default('scheduled')
                    ->required(),
            ]);
    }
}
