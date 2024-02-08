<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAudioRequest extends FormRequest
{
    public const KEY_AUDIO_ID = "audio_id";

    public function rules(): array
    {
        return [
            self::KEY_AUDIO_ID => ['required', 'exists:audio,id'],
        ];
    }

    public function all($keys = null): array
    {
        $data = parent::all();

        // Remove the extension from the id if it exists.
        $file = $this->route(self::KEY_AUDIO_ID);
        $pathParts = pathinfo($file);
        $baseName = $pathParts['filename'];
        $data[self::KEY_AUDIO_ID] = $baseName;
        return $data;
    }

    public function authorize(): bool {
        return true;
    }
}
