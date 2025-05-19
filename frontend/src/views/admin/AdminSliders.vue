<template>
  <div>
    <Breadcrumb :title="'Sliders'" :breadcrumb="breadcrumb" />
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

      <div class="card-body">
        <SliderTable :sliders="sliders" :pagination="pagination" @page-changed="fetchSliders" />
      </div>
    </div>
  </div>
</template>

<script>
import Breadcrumb from "../../components/admin/AdminBreadcrumb.vue";
import SliderTable from "../../components/admin/AdminSliderTable.vue";
import { getAllSliders } from '@/services/slider';

export default {
    components: {
        Breadcrumb,
        SliderTable,
    },
    data() {
        return {
          sliders: [],
          breadcrumb: [
              { name: "Admin", path: "/admin" },
          ],
          pagination: {},
        };
    },
    created() {
        this.fetchSliders();
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
          });
        },
    },
};
</script>

<style scoped>
.card-header {
  background-color: #f8f9fa;
}
</style>
