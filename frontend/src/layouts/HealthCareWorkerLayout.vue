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
    path: '/healthcare-worker/medical-records',
    separator: false
  },
  {
    icon: 'event_available',
    label: 'Schedule Checkup',
    path: '/healthcare-worker/schedule-checkup',
    separator: false
  },
  {
    icon: 'announcement',
    label: 'Information Board',
    path: '/healthcare-worker/information-board',
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



