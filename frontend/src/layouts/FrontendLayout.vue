<template>
  <div class="d-flex flex-column min-vh-100">
    <FrontendHeader />
    <main class="flex-fill my-3 container">
      <router-view />
    </main>
    <FrontendFooter />
  </div>
</template>

<script>
import FrontendHeader from "@/components/frontend/FrontendHeader.vue";
import FrontendFooter from "@/components/frontend/FrontendFooter.vue";

export default {
  components: {
    FrontendHeader,
    FrontendFooter,
  },
};
</script>

<style scoped>
main {
  padding: 20px;
}
</style>