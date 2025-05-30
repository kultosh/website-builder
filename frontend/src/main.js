import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "@fortawesome/fontawesome-free/css/all.min.css";
import VueMeta from 'vue-meta';

Vue.config.productionTip = false;
Vue.use(VueMeta)

new Vue({
  router,
  render: (h) => h(App),
}).$mount("#app");
