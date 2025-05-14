<template>
  <ol class="breadcrumb pt-2">
    <li v-for="(item, index) in breadcrumb" :key="index" class="breadcrumb-item">
      <router-link :to="item.path">{{ item.name }}</router-link>
    </li>
    <li class="breadcrumb-item active">{{ title }}</li>
  </ol>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    breadcrumb: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style scoped>
.breadcrumb {
  background-color: #f8f9fa;
  margin-bottom: 20px;
}
</style>
