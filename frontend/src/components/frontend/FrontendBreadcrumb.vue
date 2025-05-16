<template>
    <div class="bg-secondary text-white py-4 mb-4">
        <div class="container">
            <div class="row">
            <div class="col-md-12">
                <h1 class="mb-2">{{ title }}</h1>
                <ol class="breadcrumb mb-0">
                    <li v-for="(item, index) in breadcrumb" :key="index" class="breadcrumb-item">
                        <router-link :to="item.path">{{ item.name }}</router-link>
                    </li>
                    <li class="breadcrumb-item active">{{ title }}</li>
                </ol>
            </div>
            </div>
        </div>
    </div>

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
