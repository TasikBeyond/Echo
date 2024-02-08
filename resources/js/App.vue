<script setup>
    import {ref} from "vue";
    import SynchronizedText from "./SynchronizedText.vue";
    import { AudioApi } from "@/Api/audio-api";

    const text = ref(null);
    const audioId = ref(null);
    const audioData = ref(null);

    const createAudio = () => {
        const request = {
            text: text.value,
        }
        AudioApi.textToSpeech(request).then(response => {
            console.log('response:', response);
            audioData.value = response.data;
            audioId.value = response.data.id;
        }).catch(error => {
            console.log('error:', error);
        });
    };

    const getAudioById = () => {
        AudioApi.getAudioDataById(audioId.value).then(response => {
            console.log('response:', response);
            audioData.value = response.data;
        }).catch(error => {
            console.log('error:', error);
        });
    };
</script>


<template>
    <div class="min-h-screen bg-gray-800 text-white flex flex-col items-center justify-center">
        <div class="max-w-4xl w-full">
            <h1 class="text-3xl font-bold text-center mb-8">
                Text to Speech Synchronization Demo
            </h1>

            <!-- Create Audio From Text Section -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Create Audio From Text</h2>
                <textarea v-model="text" class="w-full p-2 bg-gray-700 rounded mb-4" placeholder="Enter text here"></textarea>
                <button @click="createAudio" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 rounded text-white">Submit</button>
            </div>

            <!-- Get Audio by Id Section -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Get Audio by Id</h2>
                <input type="text" v-model="audioId" class="w-full p-2 bg-gray-700 rounded mb-4" placeholder="Enter ID here">
                <button @click="getAudioById" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 rounded text-white">Submit</button>
            </div>

            <!-- Synchronized Text -->
            <SynchronizedText v-if="audioData" :audio-data="audioData"/>
        </div>
    </div>

</template>
