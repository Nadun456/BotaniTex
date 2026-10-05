<template>
  <!-- Menu -->

  <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo justify-content-center">
      <a href="/admin" class="app-brand-link" style="justify-content: center">
        <span class="app-brand-logo demo" style="justify-content: center">
          <img
            src="https://www.nikoba.com/images/logos/main-logo.png"
            alt
            style="height: 50px; width: auto"
          />
        </span>
        <span class="app-brand-text demo menu-text fw-bolder ms-2"></span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
      <!-- Dashboards -->
      <li class="menu-item" v-bind:class="{ active: addActiveClass(['dashboard']) }">
        <Link :href="route('dashboard')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-tachometer"></i>
          <div data-i18n="Dashboards">Dashboard</div>
        </Link>
      </li>
      <!-- Inquiry -->
      
      

      
      



      <!-- Offers -->
    </ul>
  </aside>
  <!-- / Menu -->
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
  components: {
    Link
  },
  data() {
    return {
      showingNavigationDropdown: false,
      expanded: true,
      collapsed: false,
      hidden: true,
      currentRoute: ""
    };
  },
  mounted() {
    new PerfectScrollbar(".menu-inner", {
      wheelPropagation: false,
      wheelSpeed: 0.5
    });
  },
  methods: {
    addActiveClass(routes) {
      if (routes.includes(route().current())) {
        return true;
      } else {
        return false;
      }
    }
  }
};
</script>

<style>
.rotate {
  transform: rotate(90deg);
}

.main-menu-content {
  max-height: 100vh;
  overflow-y: scroll;
  overflow-x: hidden;
}
</style>
