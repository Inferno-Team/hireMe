<template>
  <v-card
    v-ripple
    class="ma-2 pa-2 deep-purple lighten-5 pointer"
    elevation="2"
    max-width="344"
    @click="onCompanyClick"
  >
    <v-list-item three-line>
      <v-list-item-content>
        <v-list-item-title class="text-h5 mb-1">{{
          company.name
        }}</v-list-item-title>
        <v-list-item-subtitle>{{ company.description }}</v-list-item-subtitle>
        <div class="deep-purple--text mt-5">
          Available Poisition :{{ company.pos_size }}
        </div>
      </v-list-item-content>
      <v-list-item-avatar tile size="80">
        <img v-if="company.logo" :src="company.logo" alt="company logo" />
        <v-icon v-else>mdi-image-broken-variant</v-icon>
      </v-list-item-avatar>
    </v-list-item>
  </v-card>
</template>
<script>
export default {
  props: ["company"],
  methods: {
    onCompanyClick() {
      this.$router.push({
        name: "company_jobs",
        params: {
          id: this.company.id,
        },
      });
    },
  },
};
</script>
<style scoped>
.row {
  margin: 0px !important;
}
.div-50 {
  width: 50%;
  font-size: 0.875rem;
}
.pointer {
  cursor: pointer;
}
</style>