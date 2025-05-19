<template>
  <div>
    <!-- Hero / Slider Section -->
    <SliderSection :sliderList="sliderList" />
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
import SliderSection from "@/components/frontend/sections/SliderSection.vue";

export default{
    props: ['pageId'],
    components: {
        HomePageSectionSingle,
        HomePageSectionParent,
        SliderSection
    },
    data() {
        return {
            singlePageList: [],
            parentChildPageList: [],
            sliderList: [],
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
                const settings = JSON.parse(localStorage.getItem('appSettings')) || {};
                const maxSlider = settings.max_slider || 10;
                this.sliderList = data.sliders.slice(0, maxSlider);
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