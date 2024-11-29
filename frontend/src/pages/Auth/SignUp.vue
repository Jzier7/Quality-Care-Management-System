<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex justify-center items-center w-full">
        <div class="row flex flex-center signup-container w-full">
          <div class="col-12 col-md-7 q-pa-lg flex flex-center">
            <div class="text-container">
              <h2 class="text-center text-dark">Create an Account</h2>
              <p class="text-center text-dark text-sm">
                Join Lapu-Lapu District Hospital for seamless access to our services.
              </p>
            </div>

            <q-form @submit="onSubmit" @reset="onReset"
              class="full-height flex flex-center column q-pa-lg bg-dark rounded-lg form-container px-12">
              <div class="image-container">
                <img src="~/assets/logo.png" alt="Logo" />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full q-pt-md">
                <BaseInput v-model="form.first_name" label="First Name" type="text" color="primary" bgColor="white"
                  inputClass="col-span-2 md:col-span-2" />
                <BaseInput v-model="form.middle_name" label="Middle Name" type="text" color="primary" bgColor="white"
                  inputClass="col-span-2 md:col-span-2" />
                <BaseInput v-model="form.last_name" label="Last Name" type="text" color="primary" bgColor="white"
                  inputClass="col-span-2 md:col-span-2" />
                <BaseInput v-model="form.email" label="Email" type="email" color="primary" bgColor="white"
                  inputClass="col-span-2 md:col-span-2" />
                <BaseInput v-model="form.password" label="Password" type="password" color="primary" bgColor="white"
                  inputClass="col-span-2 md:col-span-1" />
                <BaseInput v-model="form.confirm_password" label="Confirm Password" type="password" color="primary"
                  bgColor="white" inputClass="col-span-2 md:col-span-1" />
              </div>

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
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import authService from '../../services/authService';

export default {
  name: 'SignUp',
  components: {
    BaseButton: defineAsyncComponent(() => import('components/Widgets/BaseButton.vue')),
    BaseInput: defineAsyncComponent(() => import('components/Widgets/BaseInput.vue')),
  },
  data() {
    return {
      form: {
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        password: '',
        confirm_password: '',
      },
    };
  },
  methods: {
    async onSubmit() {
      try {
        const response = await authService.register(this.form);

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.message
        });

        this.$router.push('/');
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response.data.message
        });
      }
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
}

.text-container {
  text-align: center;
}

.form-container {
  max-width: 700px;
  width: 100%;
  background-color: #004f97;
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
  width: 100%;
}
</style>
