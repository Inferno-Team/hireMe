<template>
  <v-card
    v-ripple
    class="ma-2 pa-2 deep-purple lighten-5 pointer"
    elevation="2"
    width="344"
    @click="downloadItem"
  >
    <v-list-item three-line>
      <v-list-item-content>
        <v-list-item-title class="text-h5 mb-1">{{
          app.user.name
        }}</v-list-item-title>
        <v-list-item-subtitle>{{ app.user.email }}</v-list-item-subtitle>
        <v-row justify="space-between">
          <div class="deep-purple--text mt-5">
            {{ app.user.phone_number }}
          </div>
          <div class="deep-purple--text text--lighten-2 mt-5 text-sm-body-2">
            {{ app.created_at }}
          </div>
        </v-row>
      </v-list-item-content>
    </v-list-item>
  </v-card>
</template>
<script>
export default {
  props: ["app"],
  methods: {
    downloadItem() {
      axios
        .get(this.app.cv_file, { responseType: "blob" })
        .then((response) => {
          const blob = new Blob([response.data], { type: "application/pdf" });
          const link = document.createElement("a");
          link.href = URL.createObjectURL(blob);
          link.download = "this.app.user.name";
          link.click();
          URL.revokeObjectURL(link.href);
        })
        .catch((err) => console.log(err));
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