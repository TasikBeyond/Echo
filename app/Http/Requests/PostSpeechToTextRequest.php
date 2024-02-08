<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostSpeechToTextRequest extends FormRequest
{
    public const KEY_TEXT = "text";

    public function rules(): array
    {
        return [
            self::KEY_TEXT => ['required', 'string', 'max:2500'],
        ];
    }
}
