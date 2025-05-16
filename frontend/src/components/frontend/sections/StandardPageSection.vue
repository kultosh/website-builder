<template>
    <section v-if="section.layout_type==2" :class="['mb-5', fromHome ? 'album py-5 px-5 bg-light' : '']">
        <div class="row">
            <div class="col-md-6" v-html="section.description"></div>
            <div class="col-md-6">
                <img :class="['img-fluid', fromHome ? 'section-layout-home' : 'section-layout-optional']" v-if="section.image_path" :src="getImageUrl(section.image_path)" :alt="section.image_alt_text">
            </div>
        </div>
    </section>
    <section v-else-if="section.layout_type==3" :class="['mb-5', fromHome ? 'album py-5 px-5 bg-light' : '']">
        <div class="row">
            <div class="col-md-6">
                <!-- <img src="https://picsum.photos/600/400" alt="Large Image" class="img-fluid"> -->
                <img :class="['img-fluid', fromHome ? 'section-layout-home' : 'section-layout-optional']" v-if="section.image_path" :src="getImageUrl(section.image_path)" :alt="section.image_alt_text">
            </div>
            <div class="col-md-6" v-html="section.description"></div>
        </div>
    </section>
    <section v-else :class="['mb-5', fromHome ? 'album py-5 px-5 bg-light' : '']">
        <div class="row">
            <div class="col-md-12">
                <img class="img-fluid" :id="fromHome ? 'section-layout-home-default' : 'section-layout-default'" v-if="section.image_path" :src="getImageUrl(section.image_path)" :alt="section.image_alt_text">
            </div>
            <div class="col-md-12" v-html="section.description"></div>
        </div>
    </section>
</template>

<script>
export default{
    props: ['section','fromHome'],
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
    .section-layout-optional {
        width: 600px;
        height: 400px;
        object-fit: cover;
    }

    #section-layout-default {
        width: 1200px;
        height: 500px;
        object-fit: cover;
    }

    .section-layout-home {
        width: 400px;
        height: 200px;
        object-fit: cover;
    }

    #section-layout-home-default {
        width: 1200px;
        height: 300px;
        object-fit: cover;
    }
</style>