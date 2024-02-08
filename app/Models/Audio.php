<?php

namespace App\Models;

use App\Traits\PrimaryUuidTrait;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    use PrimaryUuidTrait;

    const TABLE_NAME = "audio";

    const KEY_ID = "id";
    const KEY_TEXT = "text";
    const KEY_FILE_PATH = "file_path";
    const KEY_WORD_TIMESTAMPS = "word_timestamps";
    const KEY_CREATED_AT = "created_at";
    const KEY_UPDATED_AT = "updated_at";

    const APPENDS_URL = "url";

    public $appends = [
        self::APPENDS_URL,
    ];

    protected $table = self::TABLE_NAME;

    public $preventsLazyLoading = true;

    protected $fillable = [
        self::KEY_ID,
        self::KEY_TEXT,
        self::KEY_FILE_PATH,
        self::KEY_WORD_TIMESTAMPS,
        self::KEY_CREATED_AT,
        self::KEY_UPDATED_AT,
    ];

    protected $casts = [
        self::KEY_WORD_TIMESTAMPS => 'json',
    ];

    /**
     * The url is for client devices to access the voice audio file.
     * This does not use the files' storage path as storage path is not accessible to client devices.
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return env('APP_URL') . '/api/audio/' . $this->id . ".mp3";
    }
}
