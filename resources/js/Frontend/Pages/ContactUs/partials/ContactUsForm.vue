```vue
<template>
  <div class="container">
    <div class="row mx-1">

      <!-- Contact Form -->
      <div class="col-md-6 col-lg-6">
        <form
          class="row g-3 p-4 p-md-5 border rounded-3 bg-body-tertiary mt-5 mb-5"
          @submit.prevent="submit"
        >
          <div class="col-12">
            <input
              type="text"
              class="form-control"
              placeholder="First name"
              aria-label="First name"
              id="name"
              v-model="form.name"
            />
          </div>

          <div class="col-md-6">
            <input
              type="email"
              class="form-control"
              placeholder="Email"
              id="email"
              v-model="form.email"
            />
          </div>

          <div class="col-md-6">
            <input
              type="text"
              class="form-control"
              id="mobile"
              v-model="form.mobile"
              placeholder="Phone Number"
            />
          </div>

          <div class="col-12">
            <input
              type="text"
              class="form-control"
              id="subject"
              v-model="form.subject"
              placeholder="Subject"
            />
          </div>

          <div class="col-md-12">
            <textarea
              class="form-control"
              placeholder="Comments"
              style="height: 150px"
              id="comment"
              v-model="form.comment"
            ></textarea>
          </div>

          <div class="col-12">
            <button
              type="submit"
              class="btn border rounded-5 border-2 text-uppercase fw-semibold"
              :disabled="form.processing"
            >
              Submit
            </button>
          </div>
        </form>
      </div>


      <!-- Dummy Company Information -->
      <div class="col-md-6 col-lg-6">
        <div class="mt-5 mb-5 px-5">

          <p class="fs-5 text-uppercase fw-semibold">
            www.democompany.com
          </p>

          <div>

            <!-- Address -->
            <div class="row mb-3 col-md-12">
              <div class="col-1">
                <i class="fa-solid fa-house"></i>
              </div>

              <div class="col-11">
                123 Main Street, Colombo, Sri Lanka
              </div>
            </div>


            <!-- Phone -->
            <div class="row mb-3">
              <div class="col-1">
                <i class="fa-solid fa-phone"></i>
              </div>

              <div class="col-11">
                (+94) 700 000 000
              </div>
            </div>


            <!-- Email -->
            <div class="row mb-3">
              <div class="col-1">
                <i class="fa-solid fa-envelope"></i>
              </div>

              <div class="col-11">
                info@democompany.com
              </div>
            </div>


            <!-- Website -->
            <div class="row mb-3">
              <div class="col-1">
                <i class="fa-solid fa-globe"></i>
              </div>

              <div class="col-11">
                www.democompany.com
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</template>


<script>
import { Link, useForm } from "@inertiajs/inertia-vue3";

export default {
  components: {
    useForm,
    Link
  },

  data() {
    return {
      form: useForm({
        id: "",
        name: "",
        email: "",
        mobile: "",
        subject: "",
        comment: ""
      })
    };
  },

  methods: {
    submit() {
      this.form.post(
        route("contact.message.store", {
          id: this.form.id
        })
      );
    }
  }
};
</script>


<style scoped>
.form-control:focus {
  border-color: #000;
  box-shadow: none;
}
</style>
```
