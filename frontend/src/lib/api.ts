import axios from 'axios';

const api = axios.create({
	baseURL: process.env.NEXT_PUBLIC_API_BASE_URL || 'http://localhost:8000/api',
});

api.interceptors.request.use((config) => {
	if (typeof window !== 'undefined') {
		const token = localStorage.getItem('token');
		if (token) {
			config.headers = config.headers ?? {};
			config.headers.Authorization = `Bearer ${token}`;
		}
	}
	return config;
});

api.interceptors.response.use(
	(res) => res,
	(err) => {
		const status = err?.response?.status;
		if (typeof window !== 'undefined' && (status === 401 || status === 419)) {
			localStorage.removeItem('token');
			localStorage.removeItem('user');
			if (window.location.pathname !== '/login') {
				window.location.href = '/login';
			}
		}
		return Promise.reject(err);
	}
);

export default api;