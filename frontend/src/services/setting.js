import axios from 'axios';

const API_URL = process.env.VUE_APP_ROOT_API;

function getAuthHeaders() {
  return {
    headers: {
      Accept: 'application/json',
      Authorization: `Bearer ${localStorage.getItem('auth_token')}`
    }
  };
}


export function saveSettings(formData) {
  return axios.post(`${API_URL}/settings`, formData, getAuthHeaders());
}

export function updateSettings(formData) {
  return axios.post(`${API_URL}/settings`, formData, getAuthHeaders());
}

export function getAllSettings() {
  return axios.get(`${API_URL}/settings`, getAuthHeaders());
}
