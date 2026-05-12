<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedSmallInteger('duration_seconds');
            $table->unsignedTinyInteger('track_number');
            $table->string('file_url')->nullable();
            $table->unsignedBigInteger('play_count')->default(0);
            $table->boolean('explicit')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
