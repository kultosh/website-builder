<template>
    <div>
        <div v-for="page in singlePageList" :key="page.id" class="row mb-3">
            <div class="row mb-3">
                <div class="d-flex justify-content-start">
                    <span class="border-start border-info-subtle border-5 bg-light ps-2"><h2>{{ page.title }}</h2></span>
                </div>
            </div>
            <StandardPageSection :section="page" :fromHome="true" />
        </div>
    </div>
</template>

<script>
import StandardPageSection from "@/components/frontend/sections/StandardPageSection.vue";

export default{
    props: ['singlePageList'],
    components: {
        StandardPageSection,
    },
    data() {
        return {}
    }
}
</script>