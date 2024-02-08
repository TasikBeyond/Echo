<?php

use App\Http\Controllers\AudioController;
use App\Http\Controllers\SpeechSynthesisController;
use App\Http\Controllers\ToolsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->controller(ToolsController::class)->group(function () {
    Route::post('ping', 'ping');
});

Route::middleware(['api'])->controller(AudioController::class)->group(function () {
    Route::get('audio/{audio_id:uuid}/data', 'getAudioData');
    Route::get('audio/{audio_id:uuid}', 'getAudioFile');
});

Route::middleware(['api'])->controller(SpeechSynthesisController::class)->group(function () {
    Route::post('text-to-speech', 'textToSpeech');
});
