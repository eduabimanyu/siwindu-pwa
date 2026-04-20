import axios from 'axios';

// Create axios instance
const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    withCredentials: false
});

// Request interceptor - add token
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor - handle errors
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Unauthorized - clear auth and redirect to login
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user_data');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

// API endpoints
export default {
    // Authentication
    login: (email, password) => api.post('/login', { email, password }),
    logout: () => api.post('/logout'),
    getUser: () => api.get('/user'),

    // Dashboard
    getDashboardStats: () => api.get('/dashboard/stats'),

    // Transactions
    getTransactions: (params = {}) => api.get('/transactions', { params }),
    getTransactionDetail: (id) => api.get(`/transactions/${id}`),
    getTransactionThermalData: (id) => api.get(`/transactions/${id}/thermal`),
    getTopProducts: (params = {}) => api.get('/transactions/top-products', { params }),
    createTransaction: (data) => api.post('/transactions', data),

    // Shifts
    getShifts: (params = {}) => api.get('/shifts', { params }),
    getShiftDetail: (id) => api.get(`/shifts/${id}`),
    startShift: (data) => api.post('/shifts/start', data),
    endShift: (data) => api.post('/shifts/end', data),

    // Master Data
    getItems: (wisataId = null) => {
        const params = wisataId ? { wisata: wisataId } : {};
        return api.get('/items', { params });
    },
    getWisata: () => api.get('/wisata'),
    getKategori: () => api.get('/kategori'),
    getBanks: () => api.get('/banks'),
    getEwalets: () => api.get('/ewalets'),
};
