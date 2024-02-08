import axios from 'axios';

export const AudioApi = {
    textToSpeech(data) {
        return axios.post(`/api/text-to-speech`, data)
    },
    getAudioDataById(id) {
        return axios.get(`/api/audio/${id}/data`)
    },
    getAudioFileById(id) {
        return axios.get(`/api/audio/${id}`)
    }
}
