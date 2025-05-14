<template>
    <div>
        <Breadcrumb :title="'Dashboard'" :breadcrumb="breadcrumb" />
    </div>
</template>

<script>
import Breadcrumb from "../../components/admin/AdminBreadcrumb.vue";

export default {
    components: {
        Breadcrumb,
    },
    data() {
        return {
            breadcrumb: [
                { name: "Admin", path: "/admin" },
            ],
        }
    },
};
</script>