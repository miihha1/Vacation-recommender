<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->char('country_code', 2);
            $table->string('country_name', 120);
            $table->string('capital', 120);
            $table->char('currency_code', 3);
            $table->string('currency_name', 80);
            $table->string('types', 160);
            $table->decimal('flight_hours', 3, 1);
            $table->decimal('latitude', 9, 6);
            $table->decimal('longitude', 9, 6);
            $table->string('image_url', 500);
            $table->text('summary');
            $table->timestamps();
        });

        Schema::create('climates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('month');
            $table->decimal('avg_min', 4, 1);
            $table->decimal('avg_max', 4, 1);
            $table->unique(['destination_id', 'month']);
        });

        Schema::create('countries', function (Blueprint $table): void {
            $table->char('code', 2)->primary();
            $table->string('name', 120);
            $table->string('capital', 120);
        });

        Schema::create('airports', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 160);
            $table->char('iata', 3);
            $table->decimal('latitude', 9, 6);
            $table->decimal('longitude', 9, 6);
        });

        Schema::create('visits', function (Blueprint $table): void {
            $table->id();
            $table->string('visitor_hash', 64);
            $table->string('user_agent', 255)->nullable();
            $table->dateTime('visited_at');
            $table->index('visited_at');
            $table->index(['visitor_hash', 'visited_at']);
        });

        Schema::create('search_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('travel_month');
            $table->unsignedInteger('days_count');
            $table->string('types', 160);
            $table->string('temperature_pref', 20);
            $table->string('distance_pref', 20);
            $table->dateTime('searched_at');
            $table->index('searched_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_logs');
        Schema::dropIfExists('visits');
        Schema::dropIfExists('airports');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('climates');
        Schema::dropIfExists('destinations');
    }
};
