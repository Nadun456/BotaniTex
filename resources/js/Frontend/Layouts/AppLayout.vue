<template>
  <Topnavigationbar style="top: 0;" />

  <slot />

  <button class="btn-scroll-top">
  <i class="fa-solid fa-chevron-up"></i>
</button>
  <Footer />
</template>
<script setup>
import { useRecaptchaProvider } from "vue-recaptcha";
useRecaptchaProvider();
</script>

<script>
import Topnavigationbar from "./partials/Topnavigationbar.vue";
import Footer from "./partials/Footer.vue";

export default {
  components: {
    Topnavigationbar,
    Footer
  },
  mounted() {
    $(".btn-scroll-top").on("click", function() {
      $("html, body").animate(
        {
          scrollTop: 0
        },
        0
      );
    });
    jQuery(window).on("scroll", function() {
      //Scroll top Hide Show
      if ($(window).scrollTop() >= 400) {
        $(".btn-scroll-top").addClass("show");
      } else {
        $(".btn-scroll-top").removeClass("show");
      }
    }); // End Scroll Function
  }
};
</script>

<style>
</style>
