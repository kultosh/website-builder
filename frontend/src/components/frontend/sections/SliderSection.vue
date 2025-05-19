<template>
  <section class="slider-section mb-5">
    <div
      id="carouselExampleIndicators"
      class="carousel slide"
      data-bs-ride="carousel"
    >
        <template v-if="sliderList.length">
            <!-- Carousel Indicators -->
            <div class="carousel-indicators">
                <button
                v-for="(slider, index) in sliderList"
                :key="slider.id"
                type="button"
                data-bs-target="#carouselExampleIndicators"
                :data-bs-slide-to="index"
                :class="{ active: index === 0 }"
                :aria-label="`Slide ${index + 1}`"
                :aria-current="index === 0 ? 'true' : null"
                ></button>
            </div>

            <!-- Carousel Slides -->
            <div class="carousel-inner">
                <div v-for="(slider, index) in sliderList" :key="slider.id" class="carousel-item" :class="{ active: index === 0 }" >
                <img :src="getImageUrl(slider.media.path)" class="d-block w-100" :alt="slider.media.alt_text" />
                <div v-if="slider.title || slider.caption || slider.url" class="carousel-caption d-none d-md-block">
                    <h5>{{ slider.title }}</h5>
                    <p v-if="slider.caption">{{ slider.caption }}</p>
                    <p v-if="slider.url">
                    <a :href="slider.url" class="btn btn-outline-info">Redirect</a>
                    </p>
                </div>
                </div>
            </div>

            <!-- Controls -->
            <button class="carousel-control-prev" v-if="sliderList.length > 1" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" v-if="sliderList.length > 1" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </template>

        <!-- Show placeholder if no sliders -->
        <div v-else class="carousel-inner">
            <div class="carousel-item active">
                <div class="welcome-banner d-flex align-items-center justify-content-center text-center">
                    <div>
                        <h1 class="text-white">Welcome</h1>
                        <p class="text-light">This is your homepage banner</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>
</template>

<script>
export default{
    props: ['sliderList'],
    data() {
        return {}
    },
    methods: {
        getImageUrl(path) {
            const baseUrl = process.env.VUE_APP_ASSET_BASE_URL;
            return `${baseUrl}/storage/${path}`;
        },
    }
}
</script>
<style scoped>
    .carousel-item img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
    }

    .welcome-banner {
        width: 100%;
        height: 400px;
        background: linear-gradient(to right, #007bff, #00c6ff);
        color: white;
    }
</style>