<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Setting Identification
            |--------------------------------------------------------------------------
            |
            | The 'key' is the unique identifier for the setting (e.g., 'company-name')
            | The 'value' stores the actual setting value (nullable for optional settings)
            |
            */
            // Unique setting identifier (snake_case or kebab-case recommended)
            $table->string('key');

            // The setting value (as text to accommodate various types)
            $table->text('value')->nullable();

            // E.g., 'system', 'tenant', 'user'
            $table->string('level');

            $table->unsignedBigInteger('model_id')->nullable();

            // Data type for proper value casting
            $table->string('type')
                ->default('string');

            // Whether the setting is currently active
            $table->boolean('active')
                ->default(true);

            // Whether the setting can be reset to default
            $table->boolean('is_resettable')
                ->nullable()
                ->defaut(false);

            // Optimized index for common queries
            $table->index([
                'key',
                'level',
                'model_id'
            ]);

            // Ensures setting uniqueness per context
            $table->unique([
                'key',
                'level',
                'model_id'
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
