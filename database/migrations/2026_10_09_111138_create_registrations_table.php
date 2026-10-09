<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workshop_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('attendee_name');
            $table->string('attendee_email');

            $table->string('status')->default('active');

            $table->foreignId('registered_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('registered_at');

            $table->foreignId('cancelled_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

            $table->index(['workshop_id', 'status']);
            $table->index(['registered_by', 'registered_at']);
            $table->index(['cancelled_by', 'cancelled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};