<template>
  <q-select v-model="inputValue" :label="label" :outlined="outlined" :standout="standout"
    :class="`${inputClass} w-full`" :error-message="errorMessage" :error="errorMessage !== '' ? true : false"
    :options="options" option-label="label" option-value="value" />
</template>

<script>
export default {
  name: 'BaseSelect',
  props: {
    modelValue: {
      type: [String, Object],
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    outlined: {
      type: Boolean,
      default: true,
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
      default: '',
    },
    options: {
      type: Array,
      required: true,
    },
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
};
</script>
