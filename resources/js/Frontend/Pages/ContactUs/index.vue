<template>
    <AppLayout>
        <HeaderBanner />
        <ContactUsForm/>
        
    </AppLayout>
</template>

<script>
import AppLayout from "@@/Layouts/AppLayout.vue";
import HeaderBanner from "./partials/HeaderBanner.vue";
import ContactUsForm from "./partials/ContactUsForm.vue";
export default {
    components: {
        AppLayout,
        HeaderBanner,
        ContactUsForm,
    },
};
</script>

<style></style>
