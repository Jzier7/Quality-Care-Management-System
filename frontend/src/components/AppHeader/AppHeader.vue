<template>
  <q-header elevated class="py-3 bg-dark">
    <q-toolbar>
      <q-btn flat dense round aria-label="Menu" @click="$emit('toggle-drawer')">
        <q-avatar>
          <img src="~/assets/logo.png" alt="Logo" />
        </q-avatar>
      </q-btn>

      <q-toolbar-title>
        Lapu-Lapu District Hospital
      </q-toolbar-title>

      <!-- <BaseButtonLink icon="notifications" color="white" class="py-2" :onClick="handleNotifications" /> -->
      <BaseButtonLink v-show="!isAdmin" icon="account_circle" color="white" class="py-2" :onClick="goToProfile" />
      <BaseButtonLink icon="logout" color="white" class="py-2" :onClick="logout" />
    </q-toolbar>
  </q-header>
</template>

<script>
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import { useAuthStore } from 'src/stores/modules/authStore';
import { USER_ROLES } from 'src/utils/constants';
import { useUserStore } from 'src/stores/modules/userStore';

export default {
  name: 'AppHeader',
  components: {
    BaseButtonLink: defineAsyncComponent(() => import('components/Widgets/BaseButtonLink.vue')),
  },
  props: {
    version: {
      type: String,
      required: true
    }
  },
  computed: {
    isAdmin() {
      const userStore = useUserStore();
      return userStore.userData?.role.slug === USER_ROLES.ADMIN;
    },
  },
  methods: {
    async logout() {
      const authStore = useAuthStore();
      const { message } = await authStore.logout();

      Notify.create({
        type: 'positive',
        position: 'top',
        message: message
      });

      this.$router.push('/');
    },
    handleNotifications() {
      // Handle notification logic here
      Notify.create({
        type: 'info',
        position: 'top',
        message: 'No new notifications'
      });
    },
    goToProfile() {
      const userStore = useUserStore();
      const role = userStore.userData?.role?.slug;

      if (role === USER_ROLES.WORKER) {
        this.$router.push('/healthcare-worker/user-profile');
      } else if (role === USER_ROLES.PATIENT) {
        this.$router.push('/patient/user-profile');
      }
    }
  }
}
</script>
