<template>
  <q-page class="q-pa-md">
    <q-toolbar class="q-pa-none">
      <q-toolbar-title>
        <h1 class="text-dark text-3xl font-bold">Emergency Contact</h1>
      </q-toolbar-title>
    </q-toolbar>

    <div class="q-gutter-md">
      <q-carousel v-model="slide" transition-prev="jump-right" transition-next="jump-left" swipeable animated
        control-color="dark" prev-icon="arrow_left" next-icon="arrow_right" navigation padding arrows
        class="bg-white text-dark shadow-1 rounded-borders" height="750px">
        <q-carousel-slide v-for="(contact, index) in emergencyContacts" :key="contact.id" :name="`contact-${index}`"
          class="column no-wrap flex-center">
          <div class="q-mt-md text-center">
            <img v-if="contact.files && contact.files.length > 0" :src="getMediaURL(contact.files[0])" alt="Image" />
            <div v-else>No image available</div>
          </div>
        </q-carousel-slide>
      </q-carousel>
    </div>
  </q-page>
</template>

<script>
import emergencyContactService from 'src/services/emergencyContactService'
import handleMedia from 'src/utils/mixin/handleMedia';

export default {
  mixins: [handleMedia],
  data() {
    return {
      slide: '',
      emergencyContacts: []
    }
  },
  methods: {
    async fetchEmergencyContacts() {
      try {
        const response = await emergencyContactService.getAllEmergencyContacts()
        this.emergencyContacts = response.data.body

        if (this.emergencyContacts.length > 0) {
          this.slide = `contact-0`
        }
      } catch (error) {
        console.error('Error fetching emergency contacts:', error)
      }
    }
  },
  mounted() {
    this.fetchEmergencyContacts()
  }
}
</script>
