<template>
    <div class="row">
        <div class="col-md-4" v-for="childPage in childPageList" :key="childPage.id">
            <div class="card" style="width: 18rem;">
                <img class="img-fluid card-img-top page-card-image" v-if="childPage.image_path" :src="getImageUrl(childPage.image_path)" :alt="childPage.image_alt_text">
                <div class="card-body">
                    <h5 class="card-title">{{ childPage.title }}</h5>
                    <p class="card-text" v-html="childPage.description"></p>
                    <router-link :to="`/${childPage.slug}`" class="btn btn-primary">
                        Read More
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { getChildPages } from '@/services/frontendApi';

export default{
    props: ['pageId'],
    data() {
        return {
            childPageList: [],
        }
    },
    mounted() {
        this.fetchChildPages();
    },
    methods: {
        async fetchChildPages() {
            await getChildPages(this.pageId)
            .then((response) => {
                console.log('ParentPageResponse>>', response);
                this.childPageList = response.data.content;
            })
            .catch(err => {
                console.error('Page not found', err);
                this.$router.replace('/');
            });
        },

        getImageUrl(path) {
            const baseUrl = process.env.VUE_APP_ASSET_BASE_URL;
            return `${baseUrl}/storage/${path}`;
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
