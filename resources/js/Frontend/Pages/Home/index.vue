<template>
    <AppLayout>
        <TopBanner />
        <CaregorySection />
        <secondBanner />
        <thirdBanner />
    </AppLayout>
</template>

<script>
// import $ from 'jquery';
import AppLayout from "@@/Layouts/AppLayout.vue";
import TopBanner from "./partials/TopBanner.vue";
import CaregorySection from "./partials/categorySection.vue";
import secondBanner from "./partials/secondBanner.vue";
import thirdBanner from "./partials/thirdBanner.vue";

export default {
    name: "HomePage",
    components: {
        AppLayout,
        TopBanner,
        CaregorySection,

        secondBanner,
        thirdBanner,
    },
    props: {
        vehicle_types: Object,
        category_manufactures: Object,
        models: Object,
        years: Array,
        minMileage: null | Number,
        maxMileage: null | Number,
        featuredVehicles: Object,
        in_stock: Object,
        latest_cars: Object,
        reviews: Array,

        requestQuery: Object,
        auctionDates: Array,
        manufactures: Array,
        models: Array,
        years: Array,
        chassisNumbers: Array,
        engineCapacity: Array,
        colorQuery: Array,
        vehiclesList: Array,

        bikeManufactures: Object,
        bikeModel: Object,
        bikeCategory: Object,
        best_selling_product: Object,
    },
    mounted() {
        $(document).ready(function () {
            $("html, body").animate({ scrollTop: 0 }, "fastest");
            return false;
        });
    },
};
</script>

<style></style>
