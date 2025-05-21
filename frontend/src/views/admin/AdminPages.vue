<template>
  <div>
    <Breadcrumb :title="'Pages'" :breadcrumb="breadcrumb" />
    <AlertComponent v-if="alertVisible" :title="alertTitle" :message="alertMessage" :type="alertType" @close="alertVisible = false" />
    
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div>
          <i class="fas fa-table me-1"></i>
          Pages
        </div>
        <div>
          <router-link to="/admin/pages/new" class="btn btn-primary">Add Page</router-link>
        </div>
      </div>

      <div class="card-body">
        <PageTable :pages="pages" :pagination="pagination" @page-changed="fetchPages" />
      </div>
    </div>
  </div>
</template>

<script>
import Breadcrumb from "../../components/admin/AdminBreadcrumb.vue";
import PageTable from "../../components/admin/AdminPageTable.vue";
import AlertComponent from "../../components/AlertComponent.vue";
import { getAllPages } from '@/services/page';
import { EventBus } from '@/utils/eventBus';

export default {
    components: {
        Breadcrumb,
        PageTable,
        AlertComponent
    },
    data() {
        return {
          pages: [],
          breadcrumb: [
              { name: "Admin", path: "/admin" },
          ],
          pagination: {},
          alertTitle: '',
          alertMessage: '',
          alertType: 'info',
          alertVisible: false,
        };
    },
    created() {
      EventBus.$on('alert', this.showAlert);
      this.fetchPages();
    },
    beforeDestroy() {
      EventBus.$off('alert', this.showAlert);
    },
    methods: {
        fetchPages(page=1) {
          getAllPages(page)
          .then((response) => {
            const data = response.data;
            if(data.code == 200) {
              this.pages = data.content.data;
              this.pagination = {
                current_page: data.content.current_page,
                last_page: data.content.last_page,
                total: data.content.total,
                per_page: data.content.per_page,
              };
            } else {
              alert(data.message);
            }
          })
          .catch(err => {
            console.error('Failed To List Page:', err);
            alert(err);
          });
        },
        showAlert(payload) {
          this.alertTitle = payload.title;
          this.alertMessage = payload.message;
          this.alertType = payload.type;
          this.alertVisible = true;
          
          // Auto-hide after 3 seconds
          setTimeout(() => {
            this.alertVisible = false;
          }, 3000);
        }
    },
};
</script>

<style scoped>
.card-header {
  background-color: #f8f9fa;
}
</style>
