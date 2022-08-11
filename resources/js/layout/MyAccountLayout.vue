<template>
  <div style="height: 100%">
    <v-container>
      <nar-bar :selected="'-'"></nar-bar>
    </v-container>
    <div class="main-container">
      <div class="side-bar" v-if="account != null">
        <div
          style="max-width: fit-content"
          class="
            purple--text
            text--darken-2
            font-weight-bold
            text-lg-h6
            ma-auto
            mb-10
          "
        >
          Account Information
        </div>
        <v-text-field
          class="input-field mt-4"
          label="User Name"
          outlined
          v-model="account.name"
          type="text"
          color="purple darken-3"
        ></v-text-field
        ><v-text-field
          class="input-field mt-4"
          label="Email"
          outlined
          v-model="account.email"
          type="text"
          color="purple darken-3"
        ></v-text-field
        ><v-text-field
          class="input-field mt-4"
          label="Phone Number"
          outlined
          v-model="account.phone_number"
          type="text"
          color="purple darken-3"
        ></v-text-field>
        <v-btn
          block
          :loading="saveing"
          color="purple lighten-2"
          elevation="4"
          dark
          @click="save"
          >Save</v-btn
        >
      </div>
      <div class="jobs-and-page">
        <div class="jobs">
          <Job
            v-for="(job, index) in jobsToDisplay"
            :key="index"
            :job="job"
          ></Job>
        </div>
        <v-pagination
          class="card-footer pb-4 pt-3"
          :length="pageCount"
          @input="pageChanged"
        ></v-pagination>
      </div>
    </div>
  </div>
</template>

<script>
import NarBar from "../components/NarBar.vue";
import Job from "../components/Job.vue";
export default {
  components: { NarBar, Job },
  mounted() {
    let token = localStorage.getItem("hire-me-token");
    let isLoggedIn = token !== null && token !== undefined;
    if (!isLoggedIn) {
      alert("You must login to see this page");
      this.$router.push({ name: "login" });
    } else {
      this.loadMyAccount();
      this.loadMyJobs();
    }
  },
  methods: {
    loadMyAccount() {
      axios
        .post("/api/my-account")
        .then((result) => {
          this.account = result.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    loadMyJobs() {
      axios
        .post("/api/load-my-jobs")
        .then((result) => {
          this.jobs = result.data;
          this.pageChanged(1);
          this.pageCount = Math.ceil(this.jobs.length / this.itemPerPage);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    save() {
      this.saveing = true;
      axios
        .post("/api/update-account", this.account)
        .then((result) => {
          this.saveing = false;
          this.account = result.data;
        })
        .catch((err) => {
          this.saveing = false;
          console.log(err);
        });
    },
    pageChanged(page) {
      this.jobsToDisplay = [];
      let firstIndex = (page - 1) * this.itemPerPage;
      let lastIndex = firstIndex + this.itemPerPage;
      if (lastIndex > this.jobs.length) lastIndex = this.jobs.length;
      this.jobsToDisplay = this.jobs.slice(firstIndex, lastIndex);
    },
  },
  data() {
    return {
      account: null,
      saveing: false,
      jobs: [],
      jobsToDisplay: [],
      pageCount: 0,
      itemPerPage: 5,
    };
  },
};
</script>

<style scoped>
.main-container {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
.side-bar {
  width: 25rem;
  padding: 1rem;
  background-color: #fafafa;
  border-right: thin solid #ccc;
  height: calc(100vh - 104px);
}
.jobs-and-page {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.jobs {
  display: grid;
  grid-template-columns: repeat(3, minmax(92px, 0.5fr));
  justify-items: center;
  gap: 10px;
  width: 100%;
  margin: 1rem;
}
</style>