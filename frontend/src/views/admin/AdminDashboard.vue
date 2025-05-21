<template>
    <div>
        <Breadcrumb :title="'Dashboard'" :breadcrumb="breadcrumb" />

        <LoaderComponent v-if="isLoading" />
        <div class="container py-4" v-else>
            <div class="row g-4">
                <!-- Card 1 -->
                <div v-for="(card, index) in dashboardData" :key="index" class="col-md-3">
                    <div :class="['card', card.bg_class, 'shadow-sm', 'rounded-3']">
                        <div :class="['card-body', card.text_class]">
                            <h3 class="card-title mb-0">{{ card.count }}</h3>
                            <p class="card-text">{{ card.label }}</p>
                            <i :class="[card.icon, 'fs-1', 'position-absolute', 'top-0', 'end-0', 'm-4', 'opacity-25']"></i>
                        </div>
                        <div class="card-footer bg-dark bg-opacity-50 text-white">
                            More info <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import Breadcrumb from "../../components/admin/AdminBreadcrumb.vue";
import LoaderComponent from "../../components/LoaderComponent.vue";
import { getRecords } from '@/services/dashboard';

export default {
    components: {
        Breadcrumb,
        LoaderComponent
    },
    data() {
        return {
            breadcrumb: [
                { name: "Admin", path: "/admin" },
            ],
            dashboardData: [],
            isLoading: false,
        }
    },
    mounted() {
        this.dashboardRecords();
    },
    methods: {
        dashboardRecords() {
            this.isLoading = true;
            getRecords()
            .then((response) => {
                this.dashboardData = response.data.content;
            })
            .catch((e) => {
                console.log('Error!!', e);
            })
            .finally(() => {
                this.isLoading = false;
            })
        }
    }
};
</script>