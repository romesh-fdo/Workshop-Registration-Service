<?php

namespace App\Filament\Resources\Registrations\Pages;

use App\Filament\Resources\Registrations\RegistrationResource;
use App\Models\Registration;
use App\Models\Workshop;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateRegistration extends CreateRecord
{
    protected static string $resource = RegistrationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Registration {
            $workshop = Workshop::query()
                ->whereKey($data['workshop_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($workshop->status !== 'scheduled') {
                throw ValidationException::withMessages([
                    'data.workshop_id' => 'This workshop is not available for registration.',
                ]);
            }

            $activeRegistrations = Registration::query()
                ->where('workshop_id', $workshop->id)
                ->where('status', 'active')
                ->count();

            if ($activeRegistrations >= $workshop->capacity) {
                throw ValidationException::withMessages([
                    'data.workshop_id' => 'This workshop is full. No seats are available.',
                ]);
            }

            return Registration::create([
                'workshop_id' => $workshop->id,
                'attendee_name' => $data['attendee_name'],
                'attendee_email' => $data['attendee_email'],
                'status' => 'active',
                'registered_by' => auth()->id(),
                'registered_at' => now(),
            ]);
        });
    }
}