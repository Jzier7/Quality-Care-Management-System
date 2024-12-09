<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex justify-center items-center w-full">
        <q-form @submit="onSubmit" class="row shadow-xl md:w-2/4 w-full">
          <div class="col-12 col-md-5 q-pa-lg bg-primary text-white">
            <div class="full-height flex flex-center column q-py-xl">
              <h4 class="text-center">Remembered Your Details?</h4>
              <p class="text-center">Sign in to your account now!</p>
              <span class="row justify-center">
                <q-btn label="Sign in" color="white" text-color=primary type="reset" @click="navigateToSignIn" />
              </span>
            </div>
          </div>

          <div class="col-12 col-md-7 q-pa-lg">
            <div class="full-height flex flex-center column q-py-sm">
              <h2 class="text-center text-primary">Forgot Password</h2>

              <div v-if="!userExists" class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full q-pt-md">
                <q-input :error-message="errors.first_name ? errors.first_name[0] : ''" v-model="form.first_name"
                  label="First Name" class="col-span-2 md:col-span-1" />
                <q-input :error-message="errors.middle_name ? errors.middle_name[0] : ''" v-model="form.middle_name"
                  label="Middle Name" class="col-span-2 md:col-span-1" />
                <q-input :error-message="errors.last_name ? errors.last_name[0] : ''" v-model="form.last_name"
                  label="Last Name" class="col-span-2 md:col-span-1" />
                <q-input :error-message="errors.email ? errors.email[0] : ''" v-model="form.email" label="Email"
                  type="email" class="q-mb-md" />
              </div>

              <div v-else class="grid grid-cols-1 gap-4 w-full q-pt-md">
                <q-input :error-message="errors.password ? errors.password[0] : ''" v-model="passwordForm.password"
                  label="New Password" type="password" class="col-span-2" />
                <q-input :error-message="errors.confirm_password ? errors.confirm_password[0] : ''"
                  v-model="passwordForm.confirm_password" label="Confirm Password" type="password" class="col-span-2" />
              </div>

              <q-btn :label="userExists ? 'Update Password' : 'Submit'" type="submit" color="primary" class="q-mt-md" />
            </div>
          </div>
        </q-form>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import { Notify } from 'quasar';
import authService from '../../services/authService';

export default {
  name: 'ForgotPassword',
  data() {
    return {
      form: {
        first_name: '',
        middle_name: '',
        last_name: '',
        email: ''
      },
      passwordForm: {
        password: '',
        confirm_password: '',
        userId: null,
      },
      userExists: false,
      errors: {},
    };
  },
  methods: {
    navigateToSignIn() {
      this.$router.push('/');
    },
    async onSubmit() {
      if (!this.userExists) {
        try {
          const response = await authService.forgotPassword(this.form);

          Notify.create({
            type: 'positive',
            position: 'top',
            message: response.message,
          });

          this.passwordForm.userId = response.body.id;
          this.userExists = true;
        } catch (err) {
          const { data } = err.response;
          this.errors = data.errors || {};

          Notify.create({
            type: 'negative',
            position: 'top',
            message: data.message,
          });

        }
      } else {
        try {
          const payload = {
            ...this.passwordForm,
            userId: this.passwordForm.userId,
          };
          const response = await authService.updatePassword(payload);

          Notify.create({
            type: 'positive',
            position: 'top',
            message: response.message,
          });

          this.$router.push('/');
        } catch (err) {
          const { data } = err.response;
          this.errors = data.errors || {};

          Notify.create({
            type: 'negative',
            position: 'top',
            message: data.message,
          });
        }
      }
    },
  },
};
</script>
