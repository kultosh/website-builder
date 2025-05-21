<template>
    <div class="d-flex justify-content-center align-items-center loader-overlay" style="height: 50vh;">
        <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</template>

<style scoped>
.loader-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  background: rgba(255,255,255,0.7);
  z-index: 10;
}
</style>