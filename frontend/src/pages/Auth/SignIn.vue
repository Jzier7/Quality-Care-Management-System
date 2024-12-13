<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex justify-center items-center w-full">
        <div class="row flex flex-center login-container w-full">

          <div class="col-12 col-md-7 q-pa-lg flex flex-center image-container">
            <div class="text-container">
              <h1 class="text-center text-dark">Welcome to Lapu-Lapu District Hospital</h1>
              <p class="text-center text-dark mb-4 text-sm">Your health and well-being are our top priorities. Please
                sign in to access our services.</p>
            </div>

            <div class="video-container rounded-lg">
              <iframe src="https://www.youtube.com/embed/tPY-PDxFvAE?autohide=1&showinfo=0&controls=0&rel=0"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
              </iframe>
            </div>
          </div>

          <div class="col-12 col-md-5 q-pa-lg">
            <q-form @submit="onSubmit" @reset="onReset"
              class="full-height flex flex-center column q-pa-lg bg-dark rounded-lg">
              <div class="image-container">
                <img src="~/assets/logo.png" alt="Logo" />
              </div>

              <span class="w-3/4">
                <h6 class="text-white">Sign in to your account</h6>
                <BaseInput v-model="form.email" label="Email" type="email" color="primary" bgColor="white" />
                <BaseInput v-model="form.password" label="Password" type="password" color="primary" bgColor="white" />
              </span>

              <BaseButtonLink label="Forgot Password?" color="white" :onClick="forgotPassword" />
              <span class="w-3/4">
                <BaseButton label="Sign in" type="submit" color="white" textColor="grey" />
              </span>

              <div class="text-center q-mt-lg text-white">
                <span>Don't have an account?</span>
                <a @click.prevent="navigateToSignUp" class="text-secondary cursor-pointer">
                  Sign Up
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
import { useAuthStore } from 'src/stores/modules/authStore';
import { USER_ROLES } from 'src/utils/constants';

export default {
  name: 'SignIn',
  components: {
    BaseButton: defineAsyncComponent(() => import('components/Widgets/BaseButton.vue')),
    BaseButtonLink: defineAsyncComponent(() => import('components/Widgets/BaseButtonLink.vue')),
    BaseInput: defineAsyncComponent(() => import('components/Widgets/BaseInput.vue')),
  },
  data() {
    return {
      form: {
        email: '',
        password: '',
      },
      errors: {},
    };
  },
  methods: {
    async onSubmit() {
      this.errors = {};

      try {
        const authStore = useAuthStore();
        const { data, message, status } = await authStore.login(this.form);

        if (status === 401 || status === 422) {
          Notify.create({
            type: 'negative',
            position: 'top',
            message: message || 'Invalid credentials. Please check your inputs.'
          });
        } else {
          Notify.create({
            type: 'positive',
            position: 'top',
            message: message
          });

          if (data?.role.slug === USER_ROLES.ADMIN) {
            this.$router.push('/admin/dashboard');
          } else if (data?.role.slug === USER_ROLES.WORKER) {
            this.$router.push('/healthcare-worker/medical-records');
          } else {
            this.$router.push('/patient/medical-records');
          }
        }
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors || {};
        }
      }
    },
    onReset() {
      this.form = {};
      this.errors = {};
    },
    navigateToSignUp() {
      this.$router.push('/signup');
    },
    forgotPassword() {
      this.$router.push('/forgot-password');
    },
  },
};
</script>

<style lang="scss" scoped>
.login-container {
  max-width: 1500px !important;
}

.text-container {
  text-align: center;
  margin-bottom: 1rem;
}

.video-container {
  position: relative;
  width: 100%;
  padding-top: 56.25%;
  overflow: hidden;

  iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }
}

.image-container {
  display: flex;
  justify-content: center;
  align-items: center;

  img {
    padding: 1.5rem 0;
    width: 290px;
    height: auto;
  }
}
</style>
