import axios from 'axios';

//Setup für API Anfragen mit axios
const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL,
    withCredentials: false,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
    },
});

//Export der Funktionen für API Anfragen
export default {
    getKunden(page = 1, pageSize = 20, search = '') {
            return apiClient.get('/kunde', { 
            params: { 
                page, 
                pageSize, 
                search 
            } 
            });
        },
    getKunde(id) {
        return apiClient.get('/kunde/' + id);
    },
    addKunde(customer) {
        return apiClient.post('/kunde', customer);
    },
    updateKunde(id, customer) {
        return apiClient.put('/kunde/' + id, customer);
    },
    deleteKunde(id) {
        return apiClient.delete('/kunde/' + id);
    },
};
