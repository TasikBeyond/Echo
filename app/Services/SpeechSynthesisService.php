<?php

namespace App\Services;

use App\DTOs\AudioDataDTO;
use Illuminate\Support\Facades\Log;
use WebSocket\Client;

class SpeechSynthesisService
{
    public const ELEVEN_LABS_SPEECH_WEB_SOCKET_ENDPOINT = "wss://api.elevenlabs.io/v1/text-to-speech/{voice_id}/stream-input?model_id={model_id}";
    public const VOICE_ID_GEORGE_III = "jSIrNLq7hqJHOxCzIkJE"; // The Eleven Labs voice id you would like to use.
    public const VOICE_MODEL = "eleven_monolingual_v1";

    private function steamOptions(): array
    {
        return [
            'context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]),
            'timeout' => 300,
        ];
    }

    private function streamUrI(): string {
        $voiceId = self::VOICE_ID_GEORGE_III;
        $uri = str_replace("{voice_id}", $voiceId, self::ELEVEN_LABS_SPEECH_WEB_SOCKET_ENDPOINT);
        return str_replace("{model_id}", self::VOICE_MODEL, $uri);
    }

    private function streamMessage(string $text): string {
        return json_encode([
            "text" => $text,
            "voice_settings" => [
                "stability" => 0.5,
                "similarity_boost" => true
            ],
            "try_trigger_generation" => true,
            "xi_api_key" => env('ELEVEN_LABS_API_KEY'),
        ]);
    }

    private function streamEndOfStreamMessage(): string {
        return json_encode(["text" => ""]);
    }

    function textToSpeech(string $text): AudioDataDTO
    {
        $client = new Client($this->streamUrI(), $this->steamOptions());
        $client->text($this->streamMessage($text));
        $client->text($this->streamEndOfStreamMessage());

        $audioData = null;
        $startTimes = [];
        $characters = [];
        $durations = [];
        $startTimeOffsets = [];
        $offset = 0;

        // From the stream of data, we will receive audio chunks and alignment data.
        // We will use the alignment data to calculate the start times for each word.
        while ($client->isConnected()) {
            $response = $client->receive();
            $data = json_decode($response, true);

            if (!empty($data["audio"])) {
                $chunk = base64_decode($data["audio"]);
                $audioData .= $chunk;
                if (isset($data['normalizedAlignment']['charStartTimesMs'])) {
                    $startTimes = array_merge($startTimes, $data['normalizedAlignment']['charStartTimesMs']);
                    $amount = count($data['normalizedAlignment']['charStartTimesMs']);
                    $offsetArray = array_fill(0, $amount, $offset);
                    $startTimeOffsets = array_merge($startTimeOffsets, $offsetArray);
                }
                if (isset($data['normalizedAlignment']['chars'])) {
                    $characters = array_merge($characters, $data['normalizedAlignment']['chars']);
                }
                if (isset($data['normalizedAlignment']['charDurationsMs'])) {
                    $durations = array_merge($durations, $data['normalizedAlignment']['charDurationsMs']);
                }
                if (isset($data['normalizedAlignment'])) {
                    $offset += end($startTimes) + end($durations);
                }
            } else {
                break;
            }
        }

        try {
            $client->close();
        } catch (\Exception $e) {
            Log::error('Error closing websocket client: ' . $e->getMessage());
        }

        $timestamps = $this->calculateWordTimestamps($startTimes, $startTimeOffsets, $characters);
        return new AudioDataDto($text, $audioData, $timestamps);
    }


    /**
     * Timestamps are returned as milliseconds mapped to each character
     * This function calculates the start times for each word.
     * @param array $startTimes
     * @param array $startTimeOffsets
     * @param array $characters
     * @return array[]
     */
    private function calculateWordTimestamps(array $startTimes, array $startTimeOffsets, array $characters): array {
        $words = [];
        $wordStartTimes = [];
        $currentWord = '';
        $currentWordStartTime = null;

        foreach ($characters as $index => $char) {
            if ($currentWordStartTime === null) {
                $currentWordStartTime = $startTimes[$index] + $startTimeOffsets[$index];
            }

            // Append character to the current word.
            $currentWord .= $char;

            // Check if the character is a space or if we are at the end of the array.
            if ($char == ' ' || $index == count($characters) - 1) {
                if (trim($currentWord) != '') { // Do not append spaces as words.
                    $words[] = trim($currentWord);
                    $wordStartTimes[] = $currentWordStartTime;
                }

                // Reset for the next word.
                $currentWord = '';
                $currentWordStartTime = null;
            }
        }

        return [
            'words' => $words,
            'start_times' => $wordStartTimes,
        ];
    }
}
