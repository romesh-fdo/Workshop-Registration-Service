<?php

namespace App\Filament\Resources\Workshops\Tables;

use App\Models\Registration;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

                TextColumn::make('registrations_count')
                    ->label('Registered')
                    ->counts([
                        'registrations' => fn (Builder $query) =>
                            $query->where('status', 'active'),
                    ])
                    ->sortable(),

                TextColumn::make('seats_available')
                    ->label('Seats Available')
                    ->state(fn ($record): int => max(
                        0,
                        $record->capacity - Registration::query()
                            ->where('workshop_id', $record->id)
                            ->where('status', 'active')
                            ->count()
                    ))
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

                Filter::make('date_range')
                    ->label('Workshop Date Range')
                    ->schema([
                        DatePicker::make('from')
                            ->label('From Date'),

                        DatePicker::make('until')
                            ->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder =>
                                    $query->where('starts_at', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date): Builder =>
                                    $query->where(
                                        'starts_at',
                                        '<',
                                        \Carbon\Carbon::parse($date)
                                            ->addDay()
                                            ->startOfDay()
                                    )
                            );
                    }),

                Filter::make('seats_available')
                    ->label('Only workshops with available seats')
                    ->query(function (Builder $query): Builder {
                        return $query->whereRaw(
                            'capacity > (
                                SELECT COUNT(*)
                                FROM registrations
                                WHERE registrations.workshop_id = workshops.id
                                AND registrations.status = ?
                            )',
                            ['active']
                        );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),

                EditAction::make()
                    ->visible(
                        fn (): bool =>
                            auth()->user()?->can('manage workshops') ?? false
                    ),
            ]);
    }
}
