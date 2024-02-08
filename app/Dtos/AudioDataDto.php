<?php

namespace App\Dtos;

class AudioDataDto
{
    public string $text;
    public string $audioData;
    public array $timestamps;

    public function __construct(string $text, $audioData, array $timestamps)
    {
        $this->text = $text;
        $this->audioData = $audioData;
        $this->timestamps = $timestamps;
    }
}
