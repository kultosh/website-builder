import Vue from "vue";
import Router from "vue-router";
import { isAuthenticated } from "@/services/auth";

import AdminLayout from "@/layouts/AdminLayout.vue";
import Dashboard from "@/views/admin/AdminDashboard.vue";
import Pages from "@/views/admin/AdminPages.vue";
import PageForm from "@/views/admin/AdminPageForm.vue";
import Sliders from "@/views/admin/AdminSliders.vue";
import SliderForm from "@/views/admin/AdminSliderForm.vue";
import AdminLogin from "@/views/admin/auth/AdminLogin.vue";

import FrontendLayout from "@/layouts/FrontendLayout.vue";
import HomePage from "@/views/frotnend/HomePage.vue";
import PageTheme from "@/views/frotnend/PageTheme.vue";

Vue.use(Router);

const router = new Router({
  mode: "history",
  routes: [
    {
      path: "/login",
      component: AdminLogin
    },
    {
      path: "/admin",
      component: AdminLayout,
      meta: { requiresAuth: true },
      children: [
        { path: "", component: Dashboard },
        { path: "pages", component: Pages },
        { path: "pages/new", component: PageForm },
        { path: "pages/:id/edit", component: PageForm, props: true },
        { path: "sliders", component: Sliders },
        { path: "sliders/new", component: SliderForm },
        { path: "sliders/:id/edit", component: SliderForm, props: true }
      ]
    },
    {
      path: "/",
      component: FrontendLayout,
      meta: { requiresAuth: false },
      children: [
        { path: "", component: HomePage },
        // { path: "theme", component: PageTheme },
        { path: ":slug", component: PageTheme, props: true } 
      ]
    },
    { path: "*", redirect: "/login" }, // Redirect to Login page if not authorised
  ]
});

// Global Guard
router.beforeEach((to, from, next) => {
  const loggedIn = isAuthenticated();

  if (to.matched.some(record => record.meta.requiresAuth) && !loggedIn) {
    return next('/login');
  }

  if (to.path === '/login' && loggedIn) {
    return next('/admin');
  }

  next();
});

export default router;
