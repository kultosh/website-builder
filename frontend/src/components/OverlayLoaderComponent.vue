<template>
  <div v-if="visible" class="overlay-loader">
    <div class="cube-loader">
      <div class="cube"></div>
      <div class="cube"></div>
      <div class="cube"></div>
      <div class="cube"></div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    visible: {
      type: Boolean,
      required: true
    }
  }
};
</script>

<style scoped>
.overlay-loader {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 9999;
  width: 100vw;
  height: 100vh;
  background-color: rgba(255, 255, 255, 0.85);
  display: flex;
  align-items: center;
  justify-content: center;
}

.cube-loader {
  width: 60px;
  height: 60px;
  position: relative;
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}

.cube {
  width: 25px;
  height: 25px;
  background-color: #007bff;
  animation: cubeFade 1.2s infinite ease-in-out;
}

.cube:nth-child(1) { animation-delay: 0s; }
.cube:nth-child(2) { animation-delay: 0.2s; }
.cube:nth-child(3) { animation-delay: 0.4s; }
.cube:nth-child(4) { animation-delay: 0.6s; }

@keyframes cubeFade {
  0%, 100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(0.5);
    opacity: 0.3;
  }
}
</style>
