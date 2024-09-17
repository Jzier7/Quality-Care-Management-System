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
    icon: 'medical_information',
    label: 'HealthCare Workers',
    path: '/healthcare-worker/healthcare-workers',
    separator: false
  },
  {
    icon: 'campaign',
    label: 'Announcement',
    path: '/healthcare-worker/information-board',
    separator: false
  },
  {
    icon: 'forum',
    label: 'Forum',
    path: '/healthcare-worker/emergency-contact',
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



