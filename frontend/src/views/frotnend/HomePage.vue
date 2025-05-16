<template>
  <div>
    <!-- Hero / Slider Section -->
    <!-- <section class="hero-section mb-5">
      <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://via.placeholder.com/700x260?text=Slide+2" class="d-block w-100" alt="Slide 1">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Slider URL</h5>
                        <p><button type="button" class="btn btn-outline-info">Redirect</button></p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://via.placeholder.com/700x260?text=Slide+1" class="d-block w-100" alt="Slide 2">
                </div>
                    <div class="carousel-item">
                <img src="https://via.placeholder.com/700x260?text=Slide+3" class="d-block w-100" alt="Slide 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section> -->

    <!-- Single Page Sections -->
    <HomePageSectionSingle v-if="singlePageList.length" :singlePageList="singlePageList" />
    <!-- Parent Page Sections -->
    <HomePageSectionParent v-if="parentChildPageList.length" :parentPageList="parentChildPageList" />
  </div>
</template>

<script>
import { getHomePageSectionData } from '@/services/frontendApi';
import HomePageSectionSingle from "@/components/frontend/sections/HomePageSectionSingle.vue";
import HomePageSectionParent from "@/components/frontend/sections/HomePageSectionParent.vue";

export default{
    props: ['pageId'],
    components: {
        HomePageSectionSingle,
        HomePageSectionParent,
    },
    data() {
        return {
            singlePageList: [],
            parentChildPageList: [],
        }
    },
    beforeMount() {
        this.fetchSectionData();
    },
    methods: {
        async fetchSectionData() {
            await getHomePageSectionData()
            .then((response) => {
                const data = response.data.content;
                this.singlePageList = data.single;
                this.parentChildPageList = data.parent;

                console.log('singlePageList>>', this.singlePageList);
                console.log('parentChildPageList>>', this.parentChildPageList);
            })
            .catch(err => {
                console.error('Page not found', err);
                this.$router.replace('/');
            });
        },
    }
}
</script>

<style scoped>
    .card-img-top {
        height: 200px;
        object-fit: cover;
    }
</style>