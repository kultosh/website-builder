import axios from 'axios';

const API_URL = process.env.VUE_APP_ROOT_API;
const AUTH_HEADER = {
  headers: {
    Accept: 'application/json',
    Authorization: `Bearer ${localStorage.getItem('auth_token')}`
  }
};

export function saveSlider(formData) {
  return axios.post(`${API_URL}/sliders`, formData, AUTH_HEADER);
}

export function updateSlider(id, formData) {
  formData.append('_method', 'PUT');
  return axios.post(`${API_URL}/sliders/${id}`, formData, AUTH_HEADER);
}

export function getSlider(id) {
  return axios.get(`${API_URL}/sliders/${id}`, AUTH_HEADER);
}

export function getAllSliders(slider) {
  return axios.get(`${API_URL}/sliders?slider=${slider}`, AUTH_HEADER);
}

export function deleteSlider(id) {
  return axios.delete(`${API_URL}/sliders/${id}`, AUTH_HEADER);
}
