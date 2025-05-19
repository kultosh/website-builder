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

export function saveSlider(formData) {
  return axios.post(`${API_URL}/sliders`, formData, getAuthHeaders());
}

export function updateSlider(id, formData) {
  formData.append('_method', 'PUT');
  return axios.post(`${API_URL}/sliders/${id}`, formData, getAuthHeaders());
}

export function getSlider(id) {
  return axios.get(`${API_URL}/sliders/${id}`, getAuthHeaders());
}

export function getAllSliders(slider) {
  return axios.get(`${API_URL}/sliders?slider=${slider}`, getAuthHeaders());
}

export function deleteSlider(id) {
  return axios.delete(`${API_URL}/sliders/${id}`, getAuthHeaders());
}
