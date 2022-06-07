<template>
  <v-card class="card_with_boarder">
    <v-row style="flex-wrap: nowrap">
      <v-col class="center_col">
        <div class="text-h6 text--primary">Job Title</div>
        <v-text-field
          style="width: 8rem"
          filled
          rounded
          dense
          v-model="title"
          placeholder="type here.."
        ></v-text-field>
      </v-col>
      <v-divider inset vertical></v-divider>
      <v-col class="center_col">
        <div class="text-h6 text--gray">Location</div>
        <v-text-field
          style="width: 8rem"
          filled
          rounded
          dense
          v-model="location"
          placeholder="type here.."
        ></v-text-field>
      </v-col>
      <v-divider inset vertical></v-divider>
      <v-col class="center_col">
        <v-btn
          elevation="2"
          :loading="loading"
          color="orange darken-2 white--text"
          @click="search"
          rounded
          >Search
        </v-btn>
      </v-col>
    </v-row>
  </v-card>
</template>
<script>
export default {
  data() {
    return {
      loading: false,
      location: "",
      title: "",
    };
  },
  methods: {
    search() {
      const token = localStorage.getItem("hire-me-token");
      if (token === undefined || token === null) {
        alert("you need to log in first.");
        return;
      }
      this.loading = true;
      axios
        .post("/api/search", {
          location: this.location,
          title: this.title,
        })
        .then((response) => {
          this.loading = false;
          this.$router.push({
            name: "job_search",
            params: { job: JSON.stringify(response.data) },
          });
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>
<style scoped>
.card_with_boarder {
  border-radius: 1.5rem;
  padding-top: 1rem;
  padding-bottom: 1rem;
  padding-left: 4rem;
}

.col {
  padding: 0px 12px !important;
}

.center_col {
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.center_col:last-child {
  margin-right: 2rem;
  margin-left: 2rem;
}

.center_col:nth-child(1) {
  margin-right: 0.5rem;
  margin-left: 0.5rem;
}

.center_col:nth-child(3) {
  margin-right: 1.5rem;
  margin-left: 1.5rem;
}
</style>