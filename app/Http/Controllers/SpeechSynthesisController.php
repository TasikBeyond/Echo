<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostSpeechToTextRequest;
use App\Responses\AudioResponse;
use App\Services\AudioService;
use App\Services\SpeechSynthesisService;
use Illuminate\Http\JsonResponse;

class SpeechSynthesisController extends Controller
{
    public function __construct(
        private readonly SpeechSynthesisService $speechSynthesisService,
        private readonly AudioService $audioService,
    )
    {
    }

    public function textToSpeech(PostSpeechToTextRequest $request): JsonResponse
    {
        $text = $request[PostSpeechToTextRequest::KEY_TEXT];
        $audioDataDto = $this->speechSynthesisService->textToSpeech($text);
        $audioModel = $this->audioService->uploadAudioData($audioDataDto);
        return response()->json(new AudioResponse($audioModel));
    }
}
