import axios from 'axios';
window.axios = axios;

// Set default config
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL = window.location.origin;
window.axios.defaults.timeout = 30000; // 30 seconds
window.axios.defaults.withCredentials = true;

// Add CSRF token to all requests
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}

// Add response interceptor for error handling
window.axios.interceptors.response.use(
    response => response.data,
    error => {
        // Log the error for debugging
        console.error('API Error:', error.response?.data || error.message);
        
        // Handle specific error cases
        if (error.response) {
            switch (error.response.status) {
                case 419: // CSRF token mismatch
                    console.error('CSRF token mismatch. Refreshing page...');
                    window.location.reload();
                    break;
                case 401: // Unauthorized
                    console.error('Unauthorized. Redirecting to login...');
                    window.location.href = '/login';
                    break;
                case 403: // Forbidden
                    console.error('Access forbidden');
                    break;
                case 422: // Validation error
                    console.error('Validation error:', error.response.data.errors);
                    break;
                case 500: // Server error
                    console.error('Server error occurred');
                    break;
            }
        }
        
        return Promise.reject(error);
    }
);
