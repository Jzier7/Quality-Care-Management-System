<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex justify-center items-center w-full">
        <div class="row flex flex-center signup-container w-full">
          <div class="col-12 col-md-7 q-pa-lg flex flex-center">
            <div class="text-container">
              <h1 class="text-center text-dark">Create an Account</h1>
              <p class="text-center text-dark mb-4 text-sm">
                Join Lapu-Lapu District Hospital for seamless access to our services.
              </p>
            </div>

            <q-form
              @submit="onSubmit"
              @reset="onReset"
              class="full-height flex flex-center column q-pa-lg bg-dark rounded-lg form-container px-12"
            >
              <div class="image-container">
                <img src="~/assets/logo.png" alt="Logo" />
              </div>

              <span class="w-full q-mt-md">
                <BaseInput v-model="form.name" label="Full Name" type="text" color="primary" bgColor="white" />
                <BaseInput v-model="form.email" label="Email" type="email" color="primary" bgColor="white" />
                <BaseInput v-model="form.password" label="Password" type="password" color="primary" bgColor="white" />
                <BaseInput v-model="form.confirmPassword" label="Confirm Password" type="password" color="primary" bgColor="white" />
              </span>

              <BaseButton label="Sign Up" type="submit" color="white" textColor="grey" class="q-mt-lg" />

              <div class="text-center q-mt-lg text-white">
                <span>Already have an account?</span>
                <a @click.prevent="navigateToSignIn" class="text-secondary cursor-pointer">
                  Sign In
                </a>
              </div>
            </q-form>
          </div>
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
  name: 'SignUp',
  components: {
    BaseButton: defineAsyncComponent(() => import('components/Widgets/BaseButton.vue')),
    BaseInput: defineAsyncComponent(() => import('components/Widgets/BaseInput.vue')),
  },
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        confirmPassword: '',
      },
    };
  },
  methods: {
    async onSubmit() {
      // Handle form submission
    },
    navigateToSignIn() {
      this.$router.push('/');
    },
  },
};
</script>

<style lang="scss" scoped>
.signup-container {
  max-width: 1200px !important;
  padding: 2rem;
}

.text-container {
  text-align: center;
  margin-bottom: 1.5rem;
}

.form-container {
  max-width: 600px; /* Increase the width of the blue card */
  width: 100%;
  background-color: #004f97; /* Ensure it maintains the blue background */
}

.image-container {
  display: flex;
  justify-content: center;
  align-items: center;

  img {
    width: 150px;
    height: auto;
  }
}

span.w-full {
  margin-top: 1rem;
  width: 100%;
}
</style>

