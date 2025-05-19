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

export function getRecords() {
  return axios.get(`${API_URL}/dashboard`, getAuthHeaders());
}
