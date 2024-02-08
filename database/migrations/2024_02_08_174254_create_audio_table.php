<?php

use App\Models\Audio;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Audio::TABLE_NAME, function (Blueprint $table) {
            $table->uuid(Audio::KEY_ID)->primary();
            $table->text(Audio::KEY_TEXT);
            $table->string(Audio::KEY_FILE_PATH);
            $table->json(Audio::KEY_WORD_TIMESTAMPS)->nullable();
            $table->timestamps();
        });
    }
};
