<template>
  <div>
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
          <td><img :src="getImageUrl(slider.media.path)" class="d-block w-100" :alt="slider.media.alt_text" /></td>
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
      const maxLength = 20;
      if (params.length > maxLength) {
         return params.slice(0, maxLength) + '...';
      } else {
        return params;
      }
    },
    getImageUrl(path) {
        const baseUrl = process.env.VUE_APP_ASSET_BASE_URL;
        return `${baseUrl}/storage/${path}`;
    },
    deleteCurrentSlider(id) {
      const confirmed = confirm('Are you sure?');
      if (!confirmed) return;

      deleteSlider(id)
      .then((response) => {
        if(response.data.code==200) {
          this.$emit("page-changed", this.currentPage);
        }
      })
      .catch((e) => {
        alert('Error');
        console.log('Error!!', e);
      })
    }
  },
};
</script>

<style scoped>
.table {
  width: 100%;
}
</style>
