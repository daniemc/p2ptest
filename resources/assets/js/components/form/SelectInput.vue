<template>
  <div>
    <v-select
      :name="name"
      :label="label"
      :class="errorClass"
      :items="items"
      :error-messages="errorMessages"
      v-model="_value"
      item-value="value"
      item-text="text"
    ></v-select>
  </div>
</template>

<script>
export default {
  name: "select-input",

  props: {
    name: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true,
    },
    value: {
      type: String
    },
    items: {
      type: Array,
      required: false,
    },
    vErrors: {
      type: Object,
      required: true
    },
    form: {
      type: Object,
      required: true
    },
  },

  computed: {
    errorMessages () {
      return this.vErrors.collect(this.name)
    },
    errorClass () {
      return this.form.errors.has(this.name) && 'input-group--error error--text'
    },
    _value: {
      get () {
        return this.value
      },
      set (value) {
        this.$emit('update:value', value.trim())
        this.$emit('input', value.trim())
      }
    }
  }
};
</script>
