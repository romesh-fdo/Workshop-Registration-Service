<?php

namespace App\Filament\Resources\Registrations\Tables;

use App\Models\Registration;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('workshop.code')
                    ->label('Workshop Code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('workshop.title')
                    ->label('Workshop')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('attendee_name')
                    ->label('Attendee')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('attendee_email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('registered_at')
                    ->label('Registered At')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('cancelled_at')
                    ->label('Cancelled At')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),

                EditAction::make()
                    ->visible(
                        fn (Registration $record): bool =>
                            $record->status === 'active'
                    ),

                Action::make('history')
                    ->label('History')
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->modalHeading('Registration History')
                    ->modalWidth('lg')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->infolist([
                        Section::make('Activity')
                            ->schema([
                                TextEntry::make('registered_at')
                                    ->label('Registered At')
                                    ->dateTime('d M Y, H:i'),

                                TextEntry::make('registeredBy.name')
                                    ->label('Registered By')
                                    ->placeholder('Unknown user'),

                                TextEntry::make('cancelled_at')
                                    ->label('Cancelled At')
                                    ->dateTime('d M Y, H:i')
                                    ->placeholder('Not cancelled'),

                                TextEntry::make('cancelledBy.name')
                                    ->label('Cancelled By')
                                    ->placeholder('—'),

                                TextEntry::make('cancellation_reason')
                                    ->label('Cancellation Reason')
                                    ->placeholder('No reason provided')
                                    ->visible(fn (Registration $record): bool =>
                                        $record->status === 'cancelled'
                                    )
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),

            ])
            ->defaultSort('registered_at', 'desc');
    }
}
