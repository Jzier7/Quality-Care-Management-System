<template>
  <q-drawer :model-value="drawerOpen" show-if-above :width="300" :breakpoint="700" bordered
    @update:model-value="$emit('update:drawerOpen', $event)">
    <q-scroll-area class="fit">

      <div class="flex flex-col justify-center items-center py-4">
        <q-icon name="account_circle" class="text-dark" size="120px" />
        <h3 class="text-dark mt-2">{{ userName }}</h3>
      </div>

      <q-separator inset />

      <q-list>
        <template v-for="(menuItem, index) in menuList" :key="index">

          <q-item clickable v-ripple @click="navigateTo(menuItem.path)" :active="$route.path === menuItem.path"
            :class="[$route.path === menuItem.path ? 'bg-secondary text-white' : 'text-dark', 'mx-2 rounded-lg']">
            <q-item-section avatar> <q-icon :name="menuItem.icon" /> </q-item-section>
            <q-item-section> {{ menuItem.label }} </q-item-section>
          </q-item>

          <q-separator :key="'sep' + index" inset v-if="menuItem.separator" />
        </template>
      </q-list>
    </q-scroll-area>
  </q-drawer>
</template>

<script>
import { useUserStore } from 'src/stores/modules/userStore';

export default {
  name: 'SideNav',
  props: {
    links: {
      type: Array,
      required: true
    },
    drawerOpen: {
      type: Boolean,
      required: true
    },
    menuList: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      drawer: false,
    };
  },
  computed: {
    userStore() {
      return useUserStore();
    },
    userName() {
      const { first_name, last_name } = this.userStore.userData || {};
      return first_name && last_name ? `${first_name} ${last_name}` : '';
    }
  },
  methods: {
    navigateTo(path) {
      this.$router.push(path);
    },
    async fetchUserDataIfNeeded() {
      // Check if user data is already available before fetching
      if (!this.userStore.userData || Object.keys(this.userStore.userData).length === 0) {
        await this.userStore.fetchUser();
      }
    }
  },
  created() {
    this.fetchUserDataIfNeeded();
  }
};
</script>
