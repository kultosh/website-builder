import axios from 'axios';

const API_URL = process.env.VUE_APP_ROOT_API;
const API_HEADER = {
  headers: {
    Accept: 'application/json',
  }
};

export function getPageBySlug(slug) {
    return axios.get(`${API_URL}/${slug}`, API_HEADER);
}

export function getChildPages(id) {
    return axios.get(`${API_URL}/parent/${id}/childs`, API_HEADER);
}

export function getHomePageSectionData() {
    return axios.get(`${API_URL}/home`, API_HEADER);
}

export function getMenuPages() {
    return axios.get(`${API_URL}/menu/pages`);
}