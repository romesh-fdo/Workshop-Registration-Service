<?php

namespace App\Filament\Resources\Workshops\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WorkshopsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Workshop Code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('instructor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('starts_at')
                    ->label('Date & Time')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('capacity')
                    ->label('Capacity')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
