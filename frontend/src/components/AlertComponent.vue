<template>
  <div :class="['alert', alertClass]" role="alert">
    <strong>{{ title }}</strong> {{ message }}
    <button type="button" class="btn-close float-end" @click="$emit('close')"></button>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    message: String,
    type: {
      type: String,
      default: 'success'
    },
  },
  computed: {
    alertClass() {
      return {
        'alert-success': this.type === 'success',
        'alert-danger': this.type === 'danger' || this.type === 'error',
        'alert-warning': this.type === 'warning',
        'alert-info': this.type === 'info',
      };
    }
  }
};
</script>

<style scoped>
.alert {
  position: relative;
  padding: 1rem 1.5rem;
  margin-bottom: 1rem;
}
</style>
