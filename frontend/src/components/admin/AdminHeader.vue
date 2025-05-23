<template>
  <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <router-link
      :to="`/`"
      class="navbar-brand ps-3"
      :class="{ active: $route.path === '/' }"
    >
      {{ navbarHeader }}
    </router-link>
    
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
      <i class="fas fa-bars"></i>
    </button>
    
    <!-- Navbar Right Side -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
      <li class="nav-item dropdown">
        <a
          class="nav-link dropdown-toggle"
          id="navbarDropdown"
          href="#"
          role="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <i class="fas fa-user fa-fw"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
          <li><router-link to="" class="dropdown-item">Settings</router-link></li>
          <li><hr class="dropdown-divider" /></li>
          <li><button class="dropdown-item" @click="handleLogout">Logout</button></li>
        </ul>
      </li>
    </ul>
  </nav>
</template>

<script>
import { logout } from '@/services/auth';

export default {
  data() {
    return {
      navbarHeader: process.env.VUE_APP_TITLE || 'Website Builder'
    };
  },
  methods: {
    handleLogout() {
      logout()
        .then(() => this.$router.push('/login'))
        .catch(() => {
          localStorage.removeItem('auth_token');
          this.$router.push('/login');
        });
    }
  }
};
</script>


<style scoped>
.sb-topnav .navbar-brand {
  width: 225px;
  margin: 0;
}
.sb-topnav.navbar-dark #sidebarToggle {
  color: rgba(255, 255, 255, 0.5);
}
.sb-topnav.navbar-light #sidebarToggle {
  color: #212529;
}
.sb-nav-fixed .sb-topnav {
  position: fixed;
  height: 56px;
  z-index: 1039;
  top: 0;
  right: 0;
  left: 0;
}
</style>
