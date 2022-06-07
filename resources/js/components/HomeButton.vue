<template>
  <v-btn
    style="margin-right: 12px"
    :color="color"
    :loading="loading"
    rounded
    elevation="1"
    depressed
    @click="pressed"
    :dark="color != 'white'"
  >
    {{ title }}</v-btn
  >
</template>
<script>
export default {
  props: ["title", "color", "id", "loading"],
  methods: {
    pressed() {
      this.$emit("pressed", this.$props.id);
    },
  },
};
</script>