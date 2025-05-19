<template>
  <div class="d-flex flex-column min-vh-100">
    <!-- Pass settings to the header component -->
    <FrontendHeader :logo="logoUrl" :theme="themeClass" />

    <!-- Main content of the page -->
    <main class="flex-fill my-3 container">
      <router-view />
    </main>

    <!-- Footer -->
    <FrontendFooter :theme="themeClass" />
  </div>
</template>

<script>
import FrontendHeader from "@/components/frontend/FrontendHeader.vue";
import FrontendFooter from "@/components/frontend/FrontendFooter.vue";
import { getAllSettings } from '@/services/setting';

export default {
  components: {
    FrontendHeader,
    FrontendFooter,
  },
  data() {
    return {
      logoUrl: null,
      themeClass: 'bg-dark', // Default theme
    };
  },
  mounted() {
    this.fetchSettings();
  },
  methods: {
    async fetchSettings() {
      try {
        const response = await getAllSettings();
        const data = response.data.content;
        this.themeClass = data.theme;
        this.logoUrl = this.getImageUrl(data.logo);
        localStorage.setItem('appSettings', JSON.stringify(data));
      } catch (err) {
        console.error('Error fetching settings', err);
      }
    },
    getImageUrl(path) {
        const baseUrl = process.env.VUE_APP_ASSET_BASE_URL;
        return `${baseUrl}/storage/${path}`;
    }
  }
};
</script>

<style scoped>
main {
  padding: 20px;
}
</style>
