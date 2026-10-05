<template>
    <AppLayout> 
    <BannerSection/>
    <FormSection/>
    </AppLayout>
</template>

<script>
import AppLayout from "@@/Layouts/AppLayout.vue";
import BannerSection from "./partials/BannerSection.vue";
import FormSection from "./partials/FormSection.vue";

export default {
    components: {
        AppLayout,
        BannerSection,
        FormSection
        
        
        
    },
};
</script>

<style></style>
