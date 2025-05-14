import axios from 'axios';

const API_URL = process.env.VUE_APP_ROOT_API;

export function login(email, password) {
  return axios.post(`${API_URL}/login`, { email, password });
}

export function logout() {
  return axios.post(`${API_URL}/logout`, {}, {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('auth_token')}`
    }
  }).then(() => localStorage.removeItem('auth_token'));
}

export function getCurrentUser() {
  return axios.get(`${API_URL}/user`, {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('auth_token')}`
    }
  });
}

export function isAuthenticated() {
  return !!localStorage.getItem('auth_token');
}
