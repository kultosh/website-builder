<template>
  <div>
    <Breadcrumb :title="'Sliders'" :breadcrumb="breadcrumb" />
    <AlertComponent v-if="alertVisible" :title="alertTitle" :message="alertMessage" :type="alertType" @close="alertVisible = false" />
    
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div>
          <i class="fas fa-table me-1"></i>
          Sliders
        </div>
        <div>
          <router-link to="/admin/sliders/new" class="btn btn-primary">Add Slider</router-link>
        </div>
      </div>
      <LoaderComponent v-if="isLoading" />
      <div class="card-body" v-else>
        <SliderTable :sliders="sliders" :pagination="pagination" @page-changed="fetchSliders" />
      </div>
    </div>
  </div>
</template>

<script>
import Breadcrumb from "../../components/admin/AdminBreadcrumb.vue";
import SliderTable from "../../components/admin/AdminSliderTable.vue";
import LoaderComponent from "../../components/LoaderComponent.vue";
import AlertComponent from "../../components/AlertComponent.vue";
import { getAllSliders } from '@/services/slider';
import { EventBus } from '@/utils/eventBus';

// let self;
export default {
    components: {
        Breadcrumb,
        SliderTable,
        LoaderComponent,
        AlertComponent
    },
    data() {
        return {
          sliders: [],
          breadcrumb: [
              { name: "Admin", path: "/admin" },
          ],
          pagination: {},
          isLoading: true,
          alertTitle: 'success',
          alertMessage: 'This is test',
          alertType: 'info',
          alertVisible: false,
        };
    },
    created() {
        EventBus.$on('alert', this.showAlert);
        this.fetchSliders();
    },
    beforeDestroy() {
      EventBus.$off('alert', this.showAlert);
    },
    methods: {
        fetchSliders(page=1) {
          getAllSliders(page)
          .then((response) => {
            const data = response.data;
            if(data.code == 200) {
              this.sliders = data.content.data;
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
          })
          .finally(() => {
            this.isLoading = false;
          })
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
