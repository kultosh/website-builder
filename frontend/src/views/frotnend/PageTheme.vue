<template>
    <div v-if=!isLoading>
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
    <OverlayLoader v-else :visible="isLoading" />
</template>

<script>
import StandardPageSection from "@/components/frontend/sections/StandardPageSection.vue";
import StandardPageSectionParent from "@/components/frontend/sections/StandardPageSectionParent.vue";
import Breadcrumb from "@/components/frontend/FrontendBreadcrumb.vue";
import { getPageBySlug } from '@/services/frontendApi';
import OverlayLoader from '@/components/OverlayLoaderComponent.vue';

export default {
    props: ["slug"],
    components: {
        Breadcrumb,
        StandardPageSection,
        StandardPageSectionParent,
        OverlayLoader
    },

    data() {
        return {
            breadcrumb: [],
            page: {},
            sectionList: [],
            isLoading: false,
        }
    },

    metaInfo() {
        const defaultTitle = "Website Builder | Create Your Perfect Site Today";
        const defaultDescription = "Build stunning websites quickly and easily with Web Builder’s tools — no coding required";

        return {
            title: this.page.meta_title || this.page.title || defaultTitle,
            meta: [
            {
                name: 'description',
                content: this.page.meta_description || defaultDescription,
            },
            {
                property: 'og:title',
                content: this.page.meta_title || defaultTitle,
            },
            {
                property: 'og:description',
                content: this.page.meta_description || defaultDescription,
            },
            ]
        };
    },

    methods: {
        async fetchPage() {
            this.isLoading = true;
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
            })
            .finally(() => {
                this.isLoading = false;
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
        },
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