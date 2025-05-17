import axios from 'axios';

const API_URL = process.env.VUE_APP_ROOT_API;
const AUTH_HEADER = {
  headers: {
    Accept: 'application/json',
    Authorization: `Bearer ${localStorage.getItem('auth_token')}`
  }
};

export function savePage(formData) {
  return axios.post(`${API_URL}/pages`, formData, AUTH_HEADER);
}

export function updatePage(id, formData) {
  return axios.post(`${API_URL}/pages/${id}`, formData, AUTH_HEADER);
}

export function getPage(id) {
  return axios.get(`${API_URL}/pages/${id}`, AUTH_HEADER);
}

export function getAllPages(page) {
  return axios.get(`${API_URL}/pages?page=${page}`, AUTH_HEADER);
}

export function deletePage(id) {
  return axios.delete(`${API_URL}/pages/${id}`, AUTH_HEADER);
}

export function getParentPages() {
  return axios.get(`${API_URL}/parent/pages`, AUTH_HEADER);
}
