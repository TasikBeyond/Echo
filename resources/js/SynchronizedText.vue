<script setup>

import {computed, onMounted, reactive, ref, watch, watchEffect} from "vue";

const props = defineProps({
    audioData: {
        type: Object,
        default: [],
    },
});

let audio = null;
const data = reactive({
    audioTimestamp: 0,
    isPlaying: false,
})

function pauseAudio() {
    if (audio) {
        audio.pause();
        audio.currentTime = 0;
        data.isPlaying = false;
    }
}

function playAudioClicked(url, message) {
    playAudio(url, message);
}

function playAudio(url, audioData) {
    if (audio) {
        audio.pause();
        audio.currentTime = 0;
    }

    audio = new Audio(url);
    audio.playbackRate = 1.0;

    // Estimate the duration of the audio because the duration is not always available immediately.
    let words = audioData.text.split(' ').length;
    let estimatedDuration = words / 3; // 3 words per second

    data.isPlaying = false
    audio.play().then(function() {
        data.isPlaying = true;
    }).catch(function(error) {
        console.log('error:', error);
        data.isPlaying = false;
    });

    audio.addEventListener('timeupdate', function() {
        data.audioTimestamp = audio.currentTime;
        let actualDuration = isFinite(audio.duration) ? audio.duration : estimatedDuration;
        let percentagePlayed = (audio.currentTime / actualDuration) * 100;
        if (percentagePlayed >= 100) {
            data.isPlaying = false;
        }
    }, false);
}

function shouldHighlight(audioData, index) {
    if (!data.isPlaying) {
        return false;
    }

    const currentWordTime = audioData.timestamps?.start_times[index] ?? null;

    if (currentWordTime !== null) {
        const adjustedCurrentWordTime = Math.max(currentWordTime - 100, 0);
        const audioTimeInMs = data.audioTimestamp * 1000;
        if ((audioTimeInMs) > adjustedCurrentWordTime) {
            return true;
        }
    }

    return false
}

</script>

<template>
    <div class="bg-gray-900 py-0 wrapper text-white overflow-hidden">
        <div class="relative px-8 py-4 min-h-[56px] bg-gray-700">
            <span v-for="(word, index) in props.audioData.text.split(' ')" :key="index" :class="{ 'text-gray-500': shouldHighlight(props.audioData, index) && data.isPlaying }">
                {{ word }}
                <span v-if="index < props.audioData.text.split(' ').length - 1"> </span>
            </span>
            <span v-if="!data.isPlaying" class="absolute top-2 right-3 text-white cursor-pointer text-lg" @click="playAudioClicked(props.audioData.url, props.audioData)">
                ▶️
            </span>
            <span v-else class="absolute top-2 right-3 text-white cursor-pointer text-lg" @click="pauseAudio()">
                ⏸️
            </span>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in-color {
    100% {
        color: inherit;
    }
    0% {
        color: #6b7280;
    }
}

.text-gray-500 {
    animation: fade-in-color 2s ease-in forwards;
}
</style>

