<?php

namespace App\Filament\Resources\Registrations\Pages;

use App\Filament\Resources\Registrations\RegistrationResource;
use App\Models\Registration;
use App\Models\Workshop;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EditRegistration extends EditRecord
{
    protected static string $resource = RegistrationResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->status === 'cancelled') {
            abort(403, 'Cancelled registrations cannot be edited.');
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancelRegistration')
                ->label('Cancel Registration')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(
                    fn (): bool =>
                        (auth()->user()?->can('cancel registrations') ?? false)
                        && in_array(
                            $this->record->status,
                            ['active', 'waitlisted'],
                            true
                        )
                )
                ->form([
                    Textarea::make('cancellation_reason')
                        ->label('Reason for cancellation (optional)')
                        ->rows(3)
                        ->maxLength(2000)
                        ->nullable(),
                ])
                ->modalHeading('Cancel Registration')
                ->modalDescription(
                    'The registration will be retained in the history. If it occupies a seat, the earliest waitlisted attendee will be promoted automatically.'
                )
                ->modalSubmitActionLabel('Confirm Cancellation')
                ->action(function (array $data): void {
                    abort_unless(
                        auth()->user()?->can('cancel registrations'),
                        403
                    );

                    $promotedRegistration = null;

                    DB::transaction(function () use (
                        $data,
                        &$promotedRegistration
                    ): void {
                        $workshop = Workshop::query()
                            ->whereKey($this->record->workshop_id)
                            ->lockForUpdate()
                            ->firstOrFail();

                        $registration = Registration::query()
                            ->whereKey($this->record->getKey())
                            ->lockForUpdate()
                            ->firstOrFail();

                        if (! in_array(
                            $registration->status,
                            ['active', 'waitlisted'],
                            true
                        )) {
                            throw ValidationException::withMessages([
                                'cancellation' =>
                                    'This registration is already cancelled.',
                            ]);
                        }

                        $wasActive = $registration->status === 'active';

                        $registration->update([
                            'status' => 'cancelled',
                            'cancelled_by' => auth()->id(),
                            'cancelled_at' => now(),
                            'cancellation_reason' =>
                                filled($data['cancellation_reason'] ?? null)
                                    ? trim($data['cancellation_reason'])
                                    : null,
                        ]);

                        if (! $wasActive) {
                            return;
                        }

                        $promotedRegistration = Registration::query()
                            ->where('workshop_id', $workshop->id)
                            ->where('status', 'waitlisted')
                            ->orderBy('registered_at')
                            ->orderBy('id')
                            ->lockForUpdate()
                            ->first();

                        if ($promotedRegistration) {
                            $promotedRegistration->update([
                                'status' => 'active',
                                'activated_at' => now(),
                                'activated_by' => auth()->id(),
                            ]);
                        }
                    });

                    $this->record->refresh();

                    Notification::make()
                        ->title('Registration cancelled')
                        ->body(
                            $promotedRegistration
                                ? $promotedRegistration->attendee_name
                                    . ' has been promoted from the waitlist.'
                                : 'The cancellation has been recorded.'
                        )
                        ->success()
                        ->send();

                    $this->redirect(RegistrationResource::getUrl('index'));
                }),
        ];
    }
}
