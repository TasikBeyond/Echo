```
  ______     _           
 |  ____|   | |          
 | |__   ___| |__   ___  
 |  __| / __| '_ \ / _ \ 
 | |___| (__| | | | (_) |
 |______\___|_| |_|\___/                         
```

### Description

This is a simple interface to showcase ElevenLabs text-to-speech API with word highlighting/syncronization using Laravel and Vue.js.

### Project Dependencies
- Laravel
- Docker
- Vue3 
- TailwindCSS (Not strictly necessary but I always use TailwindCSS for my projects)
- textalk/websocket (ElevenLabs text-to-speech API only returns timestamps for word highlighting via a websocket connection)
- AWS S3 (for storing audio files)
- MySQL (for storing audio metadata)

### Environment Variables

Ensure these environment variables are set in your `.env` file:

- `ELEVENLABS_API_KEY` (ElevenLabs text-to-speech API key)
- `AWS_ACCESS_KEY_ID` (AWS S3 access key)
- `AWS_SECRET_ACCESS_KEY` (AWS S3 secret access key)
- `AWS_DEFAULT_REGION` (AWS S3 default region)
- `AWS_BUCKET` (AWS S3 bucket name)
- `DB_CONNECTION` (Database connection)
- `DB_HOST` (Database host)
- `DB_PORT` (Database port)
- `DB_DATABASE` (Database name)

## API Endpoints

#### POST `/api/text-to-speech` (Creates an audio file)
#### GET `/api/audio/{id}` (Downloads the .mp3 audio file)
#### GET `/api/audio/{id}/data` (Returns the word timestamps for the audio file)

## Audio Data Structure 

```
{
  "id": "5b492e71-3c40-4480-a006-bdba0572ba0d",
  "url": "http://localhost/api/audio/5b492e71-3c40-4480-a006-bdba0572ba0d.mp3",
  "text": "Welcome brave travellers.",
  "timestamps": {
    "words": ["Welcome", "brave", "travellers."],
    "start_times": [46, 441, 685]
  }
}
```

## Application 

![Application Example Image](https://github.com/TasikBeyond/Echo/assets/13050249/ebbb6494-febf-4ffb-a609-f460293efad8)

## Use in Tavern of Azoth

https://www.youtube.com/watch?v=komN4-8juGA
