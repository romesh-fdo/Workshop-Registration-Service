<div class="space-y-6">

    {{-- Workshop and Registration Status --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Workshop
                </p>

                <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                    {{ $record->workshop?->title ?? 'Workshop unavailable' }}
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Code:
                    <span class="font-medium text-gray-700 dark:text-gray-300">
                        {{ $record->workshop?->code ?? '—' }}
                    </span>
                </p>
            </div>

            @if ($record->status === 'active')
                <span class="inline-flex items-center rounded-full bg-success-50 px-3 py-1 text-xs font-semibold text-success-700 ring-1 ring-inset ring-success-600/20 dark:bg-success-400/10 dark:text-success-400">
                    Active
                </span>
            @else
                <span class="inline-flex items-center rounded-full bg-danger-50 px-3 py-1 text-xs font-semibold text-danger-700 ring-1 ring-inset ring-danger-600/20 dark:bg-danger-400/10 dark:text-danger-400">
                    Cancelled
                </span>
            @endif
        </div>
    </div>

    {{-- Attendee Details --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <h3 class="mb-4 text-sm font-semibold text-gray-950 dark:text-white">
            Attendee Details
        </h3>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Full Name
                </p>
                <p class="mt-1 text-sm font-medium text-gray-950 dark:text-white">
                    {{ $record->attendee_name }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Email Address
                </p>
                <p class="mt-1 break-all text-sm text-gray-700 dark:text-gray-300">
                    {{ $record->attendee_email }}
                </p>
            </div>
        </div>
    </div>

    {{-- History Timeline --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <div class="mb-6">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                Registration Timeline
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                A record of registration activity and changes.
            </p>
        </div>

        <div class="relative space-y-6">

            {{-- Registration Event --}}
            <div class="relative flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-success-50 text-success-600 dark:bg-success-400/10 dark:text-success-400">
                        <x-filament::icon
                            icon="heroicon-o-user-plus"
                            class="h-5 w-5"
                        />
                    </div>

                    @if ($record->status === 'cancelled')
                        <div class="mt-2 h-full w-px bg-gray-200 dark:bg-gray-700"></div>
                    @endif
                </div>

                <div class="min-w-0 flex-1 pb-2">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p class="font-semibold text-gray-950 dark:text-white">
                            Registration Created
                        </p>

                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $record->registered_at?->format('d M Y, H:i:s') ?? 'Time unavailable' }}
                        </span>
                    </div>

                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Registered by
                        <span class="font-medium text-gray-950 dark:text-white">
                            {{ $record->registeredBy?->name ?? 'Unknown user' }}
                        </span>
                    </p>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        The attendee was added to this workshop.
                    </p>
                </div>
            </div>

            {{-- Cancellation Event --}}
            @if ($record->status === 'cancelled')
                <div class="relative flex gap-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-danger-50 text-danger-600 dark:bg-danger-400/10 dark:text-danger-400">
                        <x-filament::icon
                            icon="heroicon-o-x-circle"
                            class="h-5 w-5"
                        />
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="font-semibold text-gray-950 dark:text-white">
                                Registration Cancelled
                            </p>

                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $record->cancelled_at?->format('d M Y, H:i:s') ?? 'Time unavailable' }}
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Cancelled by
                            <span class="font-medium text-gray-950 dark:text-white">
                                {{ $record->cancelledBy?->name ?? 'Unknown user' }}
                            </span>
                        </p>

                        <div class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Cancellation Reason
                            </p>

                            <p class="mt-2 whitespace-pre-wrap text-sm leading-6 text-gray-700 dark:text-gray-300">{{ filled($record->cancellation_reason) ? $record->cancellation_reason : 'No reason was provided.' }}</p>
                        </div>

                        <div class="mt-3 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <x-filament::icon
                                icon="heroicon-o-check-circle"
                                class="h-4 w-4"
                            />
                            The registration record has been retained and the seat released.
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>
