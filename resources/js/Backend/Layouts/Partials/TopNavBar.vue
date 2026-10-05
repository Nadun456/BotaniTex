<template>
  <nav
    class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar"
  >
    <div
      class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none"
    >
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="bx bx-menu bx-sm"></i>
      </a>
    </div>

    <div
      class="navbar-nav-right d-flex align-items-center"
      id="navbar-collapse"
    >
    <ul class="navbar-nav flex-row align-items-center me-auto">
      <li v-if="$page.props.branch">
        <!-- <img :src="$page.props.branch ? $page.props.branch.image : ''" alt="" style="width:45px;height:45px;object-fit:cover;"> -->
      </li>
      <li class="ms-2">
        <p class="m-0" v-if="$page.props.branch">Selected branch</p>
      <h3 class="m-0">{{$page.props.branch ? $page.props.branch.name : "Configurations"}}</h3>
      </li>
    </ul>
      <ul class="navbar-nav flex-row align-items-center ms-auto">
        <!-- branch change -->
        <li
          class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1"
          style="width: 215px"
        >
          <select
            class="select2 form-control form-select"
            id="branch_select"
            v-model="branchSelect.id"
          >
            <!-- <option selected value="">-- Select branch --</option> -->
            <option selected value="null">Super Admin</option>
            <option
              :value="branch.id"
              v-for="branch in $page.props.all_branches"
              :key="branch.id"
            >
              {{ branch.name }}
            </option>
          </select>
        </li>
        
        <!--/ Notification -->
        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
          <a
            class="nav-link dropdown-toggle hide-arrow"
            href="javascript:void(0);"
            data-bs-toggle="dropdown"
          >
            <div class="avatar avatar-online">
              <img
                :src="
                  $page.props.user.profile_photo_path
                    ? $page.props.user.profile_photo_path
                    : '/images/profile-avatar.png'
                "
                alt
                class="w-px-40 h-auto rounded-circle"
              />
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar avatar-online">
                      <img
                        :src="
                          $page.props.user.profile_photo_path
                            ? $page.props.user.profile_photo_path
                            : '/images/profile-avatar.png'
                        "
                        alt
                        class="w-px-40 h-auto rounded-circle"
                      />
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <span class="fw-semibold d-block text-capitalize">{{
                      $page.props.user.name
                    }}</span>
                    <small class="text-muted">{{
                      $page.props.user.roles.length > 0
                        ? $page.props.user.roles[0].name
                        : ""
                    }}</small>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <div class="dropdown-divider"></div>
            </li>
            <li>
              <Link class="dropdown-item" :href="route('profile')">
                <i class="bx bx-user me-2"></i>
                <span class="align-middle">My Profile</span>
              </Link>
            </li>
            <li>
              <div class="dropdown-divider"></div>
            </li>
            <li>
              <Link
                class="dropdown-item"
                :href="route('logout')"
                method="post"
                as="button"
              >
                <i class="bx bx-power-off me-2"></i>
                <span class="align-middle">Log Out</span>
              </Link>
            </li>
          </ul>
        </li>
        <!--/ User -->
      </ul>
    </div>
  </nav>
</template>
<script>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import SelectInputComponent from "@/Components/SelectInputComponent.vue";
import { Inertia } from '@inertiajs/inertia';
export default {
  components: {
    Link,
    SelectInputComponent,
  },
  props: {},
  data() {
    return {
      branchSelect: useForm({
        id: "",
      }),
    };
  },
  created() {},
  mounted() {
    var self = this;
    if(this.$page.props.branch) {
      this.branchSelect.id = this.$page.props.branch.id
    }

    $("#branch_select").select2();
    $("#branch_select").on("change", function (evt) {
      self.branchSelect.id = $(evt.target).val();
      self.branchSelect.post(route("branch.set.backend"), {
        onSuccess: () => {
          Inertia.visit(route('dashboard'));
          self.$root.showMessage(
            "success",
            '<span class="text-success">Success</span><br/>',
            "branch Change Successfully! "
          );
        },
      });
    });
  },
  computed: {},
  methods: {
    logout() {
      this.$inertia.post(route("logout"));
    },
  },
};
</script>

<style scoped>
.sidebar-toggle-icon {
  width: 2rem;
  height: 2rem;
}
</style>