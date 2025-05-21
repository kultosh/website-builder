<template>
    <div v-if=!isLoading>
        <!-- Hero / Slider Section -->
        <SliderSection :sliderList="sliderList" />
        <!-- Single Page Sections -->
        <HomePageSectionSingle v-if="singlePageList.length" :singlePageList="singlePageList" />
        <!-- Parent Page Sections -->
        <HomePageSectionParent v-if="parentChildPageList.length" :parentPageList="parentChildPageList" />
    </div>
    <OverlayLoader v-else :visible="isLoading" />
</template>

<script>
import { getHomePageSectionData } from '@/services/frontendApi';
import HomePageSectionSingle from "@/components/frontend/sections/HomePageSectionSingle.vue";
import HomePageSectionParent from "@/components/frontend/sections/HomePageSectionParent.vue";
import SliderSection from "@/components/frontend/sections/SliderSection.vue";
import OverlayLoader from '@/components/OverlayLoaderComponent.vue';

export default{
    props: ['pageId'],
    components: {
        HomePageSectionSingle,
        HomePageSectionParent,
        SliderSection,
        OverlayLoader
    },
    data() {
        return {
            singlePageList: [],
            parentChildPageList: [],
            sliderList: [],
            isLoading: false,
        }
    },
    beforeMount() {
        this.fetchSectionData();
    },
    methods: {
        async fetchSectionData() {
            this.isLoading = true;
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
            })
            .finally(() => {
                this.$nextTick(() => {
                    this.isLoading = false;
                });
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