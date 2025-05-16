<template>
     <div>
        <section class="page-sections container mb-5" v-for="childPageList in parentPageList" :key="childPageList.parent_id">
            <div class="row mb-3">
                <div class="d-flex justify-content-center">
                    <span class="border-bottom border-info-subtle border-5 bg-light ps-2"><h2>{{ childPageList.parent_page_title }}</h2></span>
                </div>
            </div>
            <div class="row album py-5 px-5 bg-light">
                <div class="col-md-4" v-for="childPage in childPageList.children" :key="childPage.id">
                    <div class="card" style="width: 18rem;">
                        <img class="img-fluid card-img-top page-card-image" v-if="childPage.image_path" :src="getImageUrl(childPage.image_path)" :alt="childPage.image_alt_text">
                        <div class="card-body">
                            <h5 class="card-title">{{ childPage.title }}</h5>
                            <p class="card-text" v-html="childPage.description"></p>
                            <router-link :to="childPage.slug" class="btn btn-primary">{{ buttonName }}</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
     </div>
</template>

<script>
export default{
    props: ['parentPageList'],
    data() {
        return {
            buttonName: 'Read More'
        }
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
    .card-img-top {
        height: 200px;
        object-fit: cover;
    }
</style>
