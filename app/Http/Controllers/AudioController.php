<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAudioRequest;
use App\Responses\AudioResponse;
use App\Services\AudioService;

class AudioController extends Controller
{
    public function __construct(
        private readonly AudioService $voiceService,
    )
    {
    }

    function getAudioData(GetAudioRequest $request): AudioResponse
    {
        $request->only([
            GetAudioRequest::KEY_AUDIO_ID,
        ]);

        $audioId = $request[GetAudioRequest::KEY_AUDIO_ID];
        $audioData = $this->voiceService->getAudioData($audioId);
        return new AudioResponse($audioData);
    }

    function getAudioFile(GetAudioRequest $request)
    {
        $request->only([
            GetAudioRequest::KEY_AUDIO_ID,
        ]);

        $audioId = $request[GetAudioRequest::KEY_AUDIO_ID];
        $audioData = $this->voiceService->getAudioFile($audioId);
        $file = $audioData['file'];
        $type = $audioData['type'];
        return response()->make($file, 200, ['Content-Type' => $type]);
    }
}
