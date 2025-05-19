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


export function savePage(formData) {
  return axios.post(`${API_URL}/pages`, formData, getAuthHeaders());
}

export function updatePage(id, formData) {
  formData.append('_method', 'PUT');
  return axios.post(`${API_URL}/pages/${id}`, formData, getAuthHeaders());
}

export function getPage(id) {
  return axios.get(`${API_URL}/pages/${id}`, getAuthHeaders());
}

export function getAllPages(page) {
  return axios.get(`${API_URL}/pages?page=${page}`, getAuthHeaders());
}

export function deletePage(id) {
  return axios.delete(`${API_URL}/pages/${id}`, getAuthHeaders());
}

export function getParentPages() {
  return axios.get(`${API_URL}/parent/pages`, getAuthHeaders());
}
