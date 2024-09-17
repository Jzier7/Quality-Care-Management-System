<template>
  <q-layout view="hHh lpr lfr">
    <AppHeader :version="$q.version" @toggle-drawer="toggleLeftDrawer" />
    <SideNav :links="linksList" :drawerOpen="leftDrawerOpen" :menuList="menuList" @update:drawerOpen="leftDrawerOpen = $event" />

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref, defineAsyncComponent } from 'vue';

const menuList = [
  {
    icon: 'folder_shared',
    label: 'Medical Records',
    path: '/patient/medical-records',
    separator: false
  },
  {
    icon: 'medical_information',
    label: 'HealthCare Workers',
    path: '/patient/healthcare-workers',
    separator: false
  },
  {
    icon: 'event_available',
    label: 'Schedule Checkup',
    path: '/patient/schedule-checkup',
    separator: true
  },
  {
    icon: 'announcement',
    label: 'Information Board',
    path: '/patient/information-board',
    separator: false
  },
  {
    icon: 'contact_phone',
    label: 'Emergency Contact',
    path: '/patient/emergency-contact',
    separator: false
  },
];

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

