import Vue from "vue";
import Router from "vue-router";

// Admin Layout and Views
import AdminLayout from "@/layouts/AdminLayout.vue";
import Dashboard from "@/views/admin/AdminDashboard.vue";
import Pages from "@/views/admin/AdminPages.vue";
import PageForm from "@/views/admin/AdminPageForm.vue";

Vue.use(Router);

export default new Router({
  mode: "history",
  routes: [
    // Admin Routes
    {
      path: "/admin",
      component: AdminLayout,
      children: [
        { path: "", component: Dashboard },
        { path: "pages", component: Pages },
        { path: "pages/new", component: PageForm },
      ],
    },
  ],
});
