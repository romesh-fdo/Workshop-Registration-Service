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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancelRegistration')
                ->label('Cancel Registration')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(
                    fn (): bool =>
                        (auth()->user()?->can('cancel registrations') ?? false)
                        && $this->record->status === 'active'
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
                    'This will cancel the registration and free the workshop seat. The registration history will be retained.'
                )
                ->modalSubmitActionLabel('Confirm Cancellation')
                ->action(function (array $data): void {
                    abort_unless(
                        auth()->user()?->can('cancel registrations'),
                        403
                    );

                    DB::transaction(function () use ($data): void {
                        $workshop = Workshop::query()
                            ->whereKey($this->record->workshop_id)
                            ->lockForUpdate()
                            ->firstOrFail();

                        $registration = Registration::query()
                            ->whereKey($this->record->getKey())
                            ->lockForUpdate()
                            ->firstOrFail();

                        if ($registration->status !== 'active') {
                            throw ValidationException::withMessages([
                                'cancellation' =>
                                    'This registration has already been cancelled.',
                            ]);
                        }

                        $registration->update([
                            'status' => 'cancelled',
                            'cancelled_by' => auth()->id(),
                            'cancelled_at' => now(),
                            'cancellation_reason' =>
                                filled($data['cancellation_reason'] ?? null)
                                    ? trim($data['cancellation_reason'])
                                    : null,
                        ]);
                    });

                    Notification::make()
                        ->title('Registration cancelled')
                        ->body('The seat has been freed and the history retained.')
                        ->success()
                        ->send();

                    $this->redirect(RegistrationResource::getUrl('index'));
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->status === 'cancelled') {
            abort(403, 'Cancelled registrations cannot be edited.');
        }

        return $data;
    }
}