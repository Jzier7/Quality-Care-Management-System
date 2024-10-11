<template>
  <div class="q-pa-md">
    <q-toolbar class="q-pa-none">
      <q-toolbar-title>
        <h1 class="text-dark text-3xl font-bold">Emergency Contact</h1>
      </q-toolbar-title>
    </q-toolbar>

    <!-- Add Image Button -->
    <q-toolbar class="q-pa-none">
      <q-btn
        label="Add Image"
        color="primary"
        @click="showAddImageDialog = true"
        class="ml-auto"
      />
    </q-toolbar>

    <!-- Image List -->
    <div class="q-gutter-md row">
      <div v-for="image in images" :key="image.id" class="col-12 col-md-4">
        <q-card>
          <q-img :src="image.url" :alt="image.title" class="fit" />

          <q-card-section>
            <div class="text-weight-bold">{{ image.title }}</div>
          </q-card-section>

          <q-card-actions align="right">
            <q-btn flat icon="delete" color="red" @click="deleteImage(image.id)" />
          </q-card-actions>
        </q-card>
      </div>
    </div>

    <!-- Add Image Dialog -->
    <q-dialog v-model="showAddImageDialog">
      <q-card>
        <q-card-section>
          <q-form @submit="addImage">
            <q-input v-model="newImage.title" label="Image Title" outlined dense />
            <q-input type="file" @change="onFileChange" label="Upload Image" outlined dense />
            <q-btn label="Add" type="submit" color="primary" class="full-width q-mt-md" />
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showAddImageDialog: false,
      newImage: {
        title: '',
        file: null,
      },
      images: [
        { id: 1, title: 'Mountain', url: 'https://example.com/mountain.jpg' },
        { id: 2, title: 'Forest', url: 'https://example.com/forest.jpg' },
      ],
    };
  },
  methods: {
    onFileChange(e) {
      const file = e.target.files[0];
      this.newImage.file = file;
    },
    addImage() {
      if (this.newImage.title && this.newImage.file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          const newId = this.images.length ? this.images[this.images.length - 1].id + 1 : 1;
          this.images.push({
            id: newId,
            title: this.newImage.title,
            url: e.target.result,
          });
          this.newImage = { title: '', file: null };
          this.showAddImageDialog = false;
        };
        reader.readAsDataURL(this.newImage.file);
      }
    },
    deleteImage(id) {
      this.images = this.images.filter((image) => image.id !== id);
    },
  },
};
</script>

<style scoped>
.fit {
  height: 250px;
  object-fit: cover;
}
</style>


