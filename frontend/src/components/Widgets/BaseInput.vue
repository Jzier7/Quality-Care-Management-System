<template>
  <q-input
    v-model="inputValue"
    :label="label"
    :type="type"
    :standout="standout"
    :class="`${inputClass} q-mt-md w-full`"
    :color="color"
    :bg-color="bgColor"
    :error-message="errorMessage"
    :error="errorMessage !== '' ?  true : false"
  />
</template>

<script>
export default {
  name: 'BaseInput',
  data() {
    return {
    }
  },
  props: {
    modelValue: {
      type: [String, Number],
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    type: {
      type: String,
      default: 'text',
    },
    standout: {
      type: String,
      default: '',
    },
    inputClass: {
      type: String,
      default: '',
    },
    errorMessage: {
      type: String,
      default: ''
    },
    color: {
      type: String,
      default: 'primary'
    },
    bgColor: {
      type: String,
      default: 'primary'
    }
  },
  computed: {
    inputValue: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit('update:modelValue', value);
      },
    },
  },
}
</script>
