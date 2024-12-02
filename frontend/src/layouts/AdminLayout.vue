<template>
  <q-layout view="hHh Lpr lff">
    <AppHeader :version="$q.version" @toggle-drawer="toggleLeftDrawer" />
    <SideNav :links="linksList" :drawerOpen="leftDrawerOpen" :menuList="menuList"
      @update:drawerOpen="leftDrawerOpen = $event" />

    <q-page-container>
      <router-view />
    </q-page-container>

  </q-layout>
</template>

<script>
import { defineComponent, ref, defineAsyncComponent } from 'vue';

const menuList = [
  {
    icon: 'medical_information',
    label: 'HealthCare Workers',
    path: '/admin/healthcare-workers',
    separator: false
  },
  {
    icon: 'person',
    label: 'Patients',
    path: '/admin/patients',
    separator: false
  },
  {
    icon: 'announcement',
    label: 'Information Board',
    path: '/admin/information-board',
    separator: false
  },
  {
    icon: 'contact_phone',
    label: 'Emergency Contact',
    path: '/admin/emergency-contact',
    separator: false
  },
]

export default defineComponent({
  name: 'AdminLayout',
  components: {
    AppHeader: defineAsyncComponent(() => import('components/AppHeader/AppHeader.vue')),
    SideNav: defineAsyncComponent(() => import('components/SideNav/SideNav.vue')),
  },
  data() {
    return {
      leftDrawerOpen: false,
      menuList
    };
  },
  methods: {
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen;
    }
  }
});
</script>
