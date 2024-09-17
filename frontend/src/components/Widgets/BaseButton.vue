<template>
  <q-btn
    :label="label"
    :color="color"
    :type="type"
    :text-color="textColor"
    :class="`${btnClass} q-mt-md p-3 w-full`"
    @click="onClick"
  />
</template>

<script>
export default {
  name: 'BaseButton',
  props: {
    label: {
      type: String,
      required: true
    },
    color: {
      type: String,
      default: 'primary'
    },
    btnClass: {
      type: String,
      default: ''
    },
    onClick: {
      type: Function,
      default: null
    },
    type: {
      type: String,
      default: ''
    },
    textColor: {
      type: String,
      default: 'white'
    }
  }
}
</script>

<style lang="scss">
.primary-btn {
  background-color: $primary !important;
  color: white !important;
}

.secondary-btn {
  background-color: white !important;
  color: $primary !important;
}
</style>

