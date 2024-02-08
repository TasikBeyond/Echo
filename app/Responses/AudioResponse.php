<?php

namespace App\Responses;

use App\Models\Audio;
use JsonSerializable;

class AudioResponse implements JsonSerializable {

    /** @var string $id */
    public string $id;

    /** @var string $url */
    public string $url;

    /** @var string $text */
    public string $text;

    /** @var array $timestamps */
    public array $timestamps;

    public function __construct(Audio $audio)
    {
        $this->id = $audio->id;
        $this->url = $audio->url;
        $this->text = $audio->text;
        $this->timestamps = $audio->word_timestamps;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "url" => $this->url,
            "text" => $this->text,
            "timestamps" => $this->timestamps,
        ];
    }
}
