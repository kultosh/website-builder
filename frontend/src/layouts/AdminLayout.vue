<template>
  <div class="sb-nav-fixed">
    <AdminHeader />
    <div id="layoutSidenav">
      <AdminSidebar />
      <div id="layoutSidenav_content">
        <main class="container-fluid px-4" style="margin-top: 60px;">
          <router-view />
        </main>
        <AdminFooter />
      </div>
    </div>
  </div>
</template>

<script>
import AdminSidebar from "../components/admin/AdminSidebar.vue";
import AdminHeader from "../components/admin/AdminHeader.vue";
import AdminFooter from "../components/admin/AdminFooter.vue";

export default {
  components: {
    AdminSidebar,
    AdminHeader,
    AdminFooter,
  },
};
</script>

<style scoped>
#layoutSidenav #layoutSidenav_nav {
  transform: translateX(0);
}
#layoutSidenav #layoutSidenav_content {
  margin-left: 0;
  transition: margin 0.15s ease-in-out;
}
.sb-nav-fixed #layoutSidenav #layoutSidenav_nav {
  width: 225px;
  height: 100vh;
  z-index: 1038;
}
.sb-nav-fixed #layoutSidenav #layoutSidenav_nav .sb-sidenav {
  padding-top: 56px;
}
.sb-nav-fixed #layoutSidenav #layoutSidenav_nav .sb-sidenav .sb-sidenav-menu {
  overflow-y: auto;
}
.sb-nav-fixed #layoutSidenav #layoutSidenav_content {
  padding-left: 225px;
  top: 56px;
}
</style>
