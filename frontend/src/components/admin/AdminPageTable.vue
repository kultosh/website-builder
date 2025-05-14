<template>
  <table class="table table-striped table-bordered" id="pagesTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="page in pages" :key="page.id">
        <td>{{ page.id }}</td>
        <td>{{ page.title }}</td>
        <td>{{ page.created_at }}</td>
        <td>
          <router-link :to="'/admin/pages/' + page.id" class="btn btn-sm btn-primary me-2">Edit</router-link>
          <button @click="deletePage(page.id)" class="btn btn-sm btn-danger">Delete</button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: {
    pages: {
      type: Array,
      required: true,
    },
  },
  methods: {
    deletePage(pageId) {
      if (confirm("Are you sure you want to delete this page?")) {
        // Simulate API call to delete page
        this.$emit("delete-page", pageId);
      }
    },
  },
};
</script>

<style scoped>
.table {
  width: 100%;
}
</style>
