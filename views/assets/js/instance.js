import axios from 'axios'

const instance = axios.create({
    baseURL: 'http://localhost/test-takers/web/api',
    headers: {
        "Content-Type": "application/json",
    }
});

export default instance