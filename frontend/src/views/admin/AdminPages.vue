<template>
  <div>
    <Breadcrumb :title="'Pages'" :breadcrumb="breadcrumb" />
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
        <PageTable :pages="pages" />
      </div>
    </div>
  </div>
</template>

<script>
import Breadcrumb from "../../components/admin/AdminBreadcrumb.vue";
import PageTable from "../../components/admin/AdminPageTable.vue";

export default {
    components: {
        Breadcrumb,
        PageTable,
    },
    data() {
        return {
        pages: [],
        breadcrumb: [
            { name: "Admin", path: "/admin" },
        ],
        };
    },
    created() {
        this.fetchPages();
    },
    methods: {
        fetchPages() {
          this.pages = [
              { id: 1, title: "Home", created_at: "2025-05-11" },
              { id: 2, title: "About Us", created_at: "2025-05-10" },
              { id: 3, title: "Services", created_at: "2025-05-09" },
              { id: 4, title: "Contact", created_at: "2025-05-08" },
          ];
        },
    },
};
</script>

<style scoped>
.card-header {
  background-color: #f8f9fa;
}
</style>
