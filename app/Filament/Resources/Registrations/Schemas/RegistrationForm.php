<?php

namespace App\Filament\Resources\Registrations\Schemas;

use App\Models\Registration;
use App\Models\Workshop;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workshop_id')
                    ->label('Workshop')
                    ->relationship(
                        'workshop',
                        'title',
                        modifyQueryUsing: fn ($query) =>
                            $query->where('status', 'scheduled')
                    )
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->helperText(function (
                        Get $get,
                        string $operation
                    ): ?HtmlString {
                        // Show the waitlist warning only on creation.
                        if ($operation !== 'create') {
                            return null;
                        }

                        $workshopId = $get('workshop_id');

                        if (! $workshopId) {
                            return null;
                        }

                        $workshop = Workshop::find($workshopId);

                        if (! $workshop) {
                            return null;
                        }

                        $activeCount = Registration::query()
                            ->where('workshop_id', $workshop->id)
                            ->where('status', 'active')
                            ->count();

                        if ($activeCount < $workshop->capacity) {
                            return null;
                        }

                        return new HtmlString(
                            '<span style="color: var(--warning-600);">'
                            . 'Warning: No seats are available. '
                            . 'Saving this registration will add the '
                            . 'attendee to the waitlist.'
                            . '</span>'
                        );
                    }),

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