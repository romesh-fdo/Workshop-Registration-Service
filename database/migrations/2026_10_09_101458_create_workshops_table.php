
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('title');
            $table->string('instructor');
            $table->dateTime('starts_at');
            $table->unsignedInteger('capacity');
            $table->string('status')->default('scheduled');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['starts_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
