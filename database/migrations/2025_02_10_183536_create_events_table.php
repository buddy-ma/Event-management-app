<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->string('address')->nullable();
            $table->integer('max_participants')->default(0);
            $table->enum('category', ['social', 'sports', 'education', 'entertainment', 'other'])
                ->default('other');
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])
                ->default('published');
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->boolean('is_online')->default(false);
            $table->string('online_url')->nullable();
            $table->string('tags')->nullable();
            $table->double('price')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
