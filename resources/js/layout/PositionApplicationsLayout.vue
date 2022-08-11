<template>
  <div style="height: 100%">
    <v-container>
      <nar-bar :selected="'-'"></nar-bar>
    </v-container>
    <div>
      <div v-if="loading" class="text-center" style="line-height: 33">
        <v-progress-circular
          indeterminate
          color="primary"
          class="m-auto"
        ></v-progress-circular>
      </div>
      <div class="app-and-page" v-if="apps.length > 0">
        <div class="apps">
          <application
            v-for="(app, index) in apps"
            :key="index"
            :app="app"
          ></application>
        </div>
      </div>

      <div v-if="!loading && apps.length == 0" class="ma-auto">
        <div style="line-height: 20" class="text-center text-lg-h6">
          There is no application for this job to display.
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NarBar from "../components/NarBar.vue";
import Application from "../components/Application.vue";
export default {
  props: ["id"],
  mounted() {
    let token = localStorage.getItem("hire-me-token");
    let isLoggedIn = token !== null && token !== undefined;
    if (!isLoggedIn) {
      alert("You must login to see this page");
      this.$router.push({ name: "login" });
    } else this.loadJobApplications();
  },
  components: { NarBar, Application },
  data() {
    return {
      loading: true,
      apps: [],
    };
  },
  methods: {
    loadJobApplications() {
      this.loading = true;
      axios
        .post(`/api/get_position_applications/${this.id}`)
        .then((result) => {
          this.loading = false;
          let data = result.data;
          console.log(data);
          if (data.code > 200) {
          } else {
            this.apps = data.applications;
          }
        })
        .catch((err) => {
          console.log(err);
          this.loading = false;
        });
    },
  },
};
</script>

<style scoped>
.app-and-page {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.apps {
  display: grid;
  grid-template-columns: repeat(3, minmax(92px, 0.5fr));
  justify-items: center;
  gap: 10px;
  width: 100%;
  margin: 1rem;
}
</style>