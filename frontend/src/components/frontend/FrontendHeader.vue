<template>
  <nav :class="theme" class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><img v-if="logo" :src="logo" alt="Logo" class="navbar-logo" /></a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <router-link
              :to="`/`"
              class="nav-link"
              :class="{ active: $route.path === '/' }"
            >
              Home
            </router-link>

          </li>
          
          <li
            v-for="page in parentMenuPages"
            :key="'parent-' + page.id"
            class="nav-item dropdown"
            :class="{ active: isParentActive(page) }"
          >
            <a
              class="nav-link dropdown-toggle"
              href="#"
              :id="`dropdown-${page.id}`"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              {{ page.title }}
            </a>
            <ul class="dropdown-menu" :aria-labelledby="`dropdown-${page.id}`">
              <li
                v-for="child in page.children"
                :key="'child-' + child.id"
              >
                <router-link
                  class="dropdown-item"
                  :to="`/${child.slug}`"
                  active-class="active"
                >
                  {{ child.title }}
                </router-link>
              </li>
            </ul>
          </li>
          <li
            v-for="page in singleMenuPages"
            :key="'single-' + page.id"
            class="nav-item"
          >
            <router-link :to="`/${page.slug}`" class="nav-link" active-class="active">
              {{ page.title }}
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>


<script>
import { getMenuPages } from "@/services/frontendApi";

export default {
  props: ['theme','logo'],
  data() {
    return {
      menuPages: [],
    };
  },
  mounted() {
    this.fetchMenuPages();
  },
  computed: {
    parentMenuPages() {
      return this.menuPages.filter(p => p.children && p.children.length);
    },
    singleMenuPages() {
      return this.menuPages.filter(p => !p.children || !p.children.length);
    },
  },
  methods: {
    async fetchMenuPages() {
      try {
        const response = await getMenuPages();
        this.menuPages = response.data.content;
      } catch (err) {
        console.error("Failed to load menu pages", err);
      }
    },
    isParentActive(parentPage) {
      const currentPath = this.$route.path;
      return parentPage.children.some(child => `/${child.slug}` === currentPath);
    }
  }
};
</script>

<style scoped>
.navbar .nav-item.active > .nav-link {
  background-color: rgba(255, 255, 255, 0.2);
  border-radius: 0.25rem;
}
.navbar-logo {
  height: 40px;
}
</style>
