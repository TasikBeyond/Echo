<?php

namespace App\Services;

use App\Dtos\AudioDataDto;
use App\Models\Audio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AudioService
{
    public function getAudioData(string $audioId): Audio
    {
        return Audio::where(Audio::KEY_ID, $audioId)->first();
    }

    public function getAudioFile(string $audioId) {
        $audio = Audio::where(Audio::KEY_ID, $audioId)->first();
        $filePath = $audio->file_path;

        if (Storage::disk('s3')->exists($filePath)) {
            $file = Storage::disk('s3')->get($filePath);
            $type = Storage::disk('s3')->mimeType($filePath);
            return [
                'file' => $file,
                'type' => $type,
            ];
        }

        abort(404, "Audio not found.");
    }

    public function uploadAudioData(AudioDataDto $audioDataDto): Audio
    {
        $randomUuid = Str::UUID();
        $audioPath = 'audio/' . $randomUuid . ".mp3";
        $uploaded = Storage::disk('s3')->put($audioPath, $audioDataDto->audioData);

        if ($uploaded) {
            return Audio::create([
                Audio::KEY_ID => $randomUuid,
                Audio::KEY_TEXT => $audioDataDto->text,
                Audio::KEY_FILE_PATH => $audioPath,
                Audio::KEY_WORD_TIMESTAMPS => $audioDataDto->timestamps,
            ]);
        }

        abort(500, "Failed to upload audio data.");
    }
}
