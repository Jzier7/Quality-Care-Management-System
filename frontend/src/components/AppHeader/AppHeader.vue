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

      <BaseButtonLink icon="notifications" color="white" class="py-2" :onClick="handleNotifications" />
      <BaseButtonLink icon="account_circle" color="white" class="py-2" :onClick="goToProfile" />
      <BaseButtonLink icon="logout" color="white" class="py-2" :onClick="logout" />
    </q-toolbar>
  </q-header>
</template>

<script>
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import { useAuthStore } from 'src/stores/modules/authStore';

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
      // Navigate to the profile page
      this.$router.push('/profile');
    }
  }
}
</script>
