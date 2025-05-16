<template>
    <div>
        <Breadcrumb v-if="page && page.title" :title="page.title" :breadcrumb="breadcrumb" />

        <div v-if="page.is_parent==1">
            <StandardPageSectionParent :pageId="page.id" /> <!-- Use loop for its child i.e. data coming from page -->
        </div>
        <div v-else>
            <div v-for="section in sectionList" :key="section.id">
                <StandardPageSection :section="section" :fromHome="false" />
            </div>
        </div>
    </div>
</template>

<script>
import StandardPageSection from "@/components/frontend/sections/StandardPageSection.vue";
import StandardPageSectionParent from "@/components/frontend/sections/StandardPageSectionParent.vue";
import Breadcrumb from "@/components/frontend/FrontendBreadcrumb.vue";
import { getPageBySlug } from '@/services/frontendApi';

export default {
    props: ["slug"],
    components: {
        Breadcrumb,
        StandardPageSection,
        StandardPageSectionParent
    },

    data() {
        return {
            breadcrumb: [],
            page: {},
            sectionList: []
        }
    },

    methods: {
        async fetchPage() {
            await getPageBySlug(this.slug)
            .then((response) => {
                const data = response.data.content;
                this.updateBreadcrumb(data);
                if(data.sections) {
                    this.sectionList = data.sections;
                }
                this.page = data;
            })
            .catch(err => {
                console.error('Page not found', err);
                this.$router.replace('/');
            });
        },
        updateBreadcrumb(data) {
            this.breadcrumb = [
                { name: "Home", path: "/" },
            ];
            if(!data.is_parent && data.parent_title != null) {
                this.breadcrumb.push(
                    {name: data.parent_title, path: '/'+data.parent_slug}
                );
            }
        }
    },

    watch: {
        slug: {
            immediate: true,
            handler(newSlug) {
                this.fetchPage(newSlug);
            }
        }
    },
}
</script>