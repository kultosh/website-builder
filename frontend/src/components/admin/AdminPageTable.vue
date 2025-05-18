<template>
  <div>
    <table class="table table-striped table-bordered" id="pagesTable">
      <thead>
        <tr>
            <th v-for="(column,colIndex) in columns" :key="colIndex">{{ column.label }}</th>
        </tr>
      </thead>
      <tbody v-if="pages.length">
        <tr v-for="(page,index) in pages" :key="page.id">
          <td>{{ index + 1 + (pagination.current_page - 1) * pagination.per_page }}</td>
          <td>{{ page.title }}</td>
          <td>{{ page.slug }}</td>
          <td>{{ toYesNo(page.add_to_home) }}</td>
          <td>{{ toYesNo(page.is_menu) }}</td>
          <td>{{ formatDate(page.created_at) }}</td>
          <td>
            <router-link :to="`/admin/pages/${page.id}/edit`" class="btn btn-sm btn-primary me-2"><i class="fas fa-pencil-alt"></i></router-link>
            <button @click="deleteCurrentPage(page.id)" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
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
import { deletePage } from '@/services/page';

export default {
  props: {
    pages: {
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
        { label: 'Slug' },
        { label: 'Add To Home' },
        { label: 'Is Menu' },
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
    toYesNo(value) {
      return value ? 'YES' : 'NO';
    },
    deleteCurrentPage(id) {
      const confirmed = confirm('Are you sure?');
      if (!confirmed) return;

      deletePage(id)
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
