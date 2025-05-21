<template>
  <div>
    <div v-if="isDeleting" class="overlay-loader d-flex justify-content-center align-items-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Deleting...</span>
      </div>
    </div>
    <table class="table table-striped table-bordered" id="pagesTable">
      <thead>
        <tr>
            <th v-for="(column,colIndex) in columns" :key="colIndex">{{ column.label }}</th>
        </tr>
      </thead>
      <tbody v-if="sliders.length">
        <tr v-for="(slider,index) in sliders" :key="slider.id">
          <td>{{ index + 1 + (pagination.current_page - 1) * pagination.per_page }}</td>
          <td>{{ slider.title }}</td>
          <td>{{ limitText(slider.caption) }}</td>
          <td>{{ slider.url }}</td>
          <td>{{ slider.order }}</td>
          <td><img :src="getImageUrl(slider.media.thumbnail_path)" class="d-block w-100" :alt="slider.media.alt_text" /></td>
          <td>{{ toActiveInactive(slider.status) }}</td>
          <td>{{ formatDate(slider.created_at) }}</td>
          <td>
            <router-link :to="`/admin/sliders/${slider.id}/edit`" class="btn btn-sm btn-primary me-2"><i class="fas fa-pencil-alt"></i></router-link>
            <button @click="deleteCurrentSlider(slider.id)" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr>
          <td :colspan="columns.length"><div class="d-flex justify-content-center w-100">No Data</div></td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <nav v-if="pagination && totalPages > 1">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <button class="page-link" @click="changePage(currentPage - 1)" :disabled="currentPage === 1">Previous</button>
        </li>

        <li
          v-for="pageNumber in totalPages"
          :key="pageNumber"
          class="page-item"
          :class="{ active: currentPage === pageNumber }"
        >
          <button class="page-link" @click="changePage(pageNumber)">{{ pageNumber }}</button>
        </li>

        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <button class="page-link" @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages">Next</button>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
import { deleteSlider} from '@/services/slider';
import { EventBus } from '@/utils/eventBus'; 

export default {
  props: {
    sliders: {
      type: Array,
      required: true,
    },
    pagination: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      columns: [
        { label: 'S.N.' },
        { label: 'Title' },
        { label: 'Caption' },
        { label: 'URL' },
        { label: 'Order' },
        { label: 'Image' },
        { label: 'Status' },
        { label: 'Created At' },
        { label: 'Actions'}
      ],
      isDeleting: false,
    }
  },
  computed: {
    currentPage() {
      return this.pagination.current_page;
    },
    totalPages() {
      return this.pagination.last_page || 0;
    },
  },
  methods: {
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.$emit("page-changed", page);
      }
    },
    formatDate(dateStr) {
      if (!dateStr) return '';
      const date = new Date(dateStr);
      return date.toISOString().split('T')[0]; // returns 'YYYY-MM-DD'
    },
    toActiveInactive(value) {
      return value ? 'Active' : 'Inactive';
    },
    limitText(params) {
      if(params!=null) {
        const maxLength = 20;
        if (params.length > maxLength) {
          return params.slice(0, maxLength) + '...';
        } else {
          return params;
        }
      }
    },
    getImageUrl(path) {
        const baseUrl = process.env.VUE_APP_ASSET_BASE_URL;
        return `${baseUrl}/storage/${path}`;
    },
    deleteCurrentSlider(id) {
      const confirmed = confirm('Are you sure?');
      if (!confirmed) return;

      this.isDeleting = true;

      deleteSlider(id)
      .then((response) => {
        const responseData = response.data;
        const alertTitle = responseData.code == 200 ? 'Success:' : 'Error:';
        EventBus.$emit('alert', {
          title: alertTitle,
          message: responseData.message,
          type: responseData.status
        });
      })
      .catch((e) => {
        EventBus.$emit('alert', {
          title: 'Error',
          message: e.response?.data?.message || 'Failed to delete slider',
          type: 'error'
        });
      })
      .finally(() => {
        this.isDeleting = false;
        this.$emit("page-changed", this.currentPage);
      });
    }
  },
};
</script>

<style scoped>
.table {
  width: 100%;
}

.overlay-loader {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background-color: rgba(255, 255, 255, 0.6);
  z-index: 10;
}
</style>
