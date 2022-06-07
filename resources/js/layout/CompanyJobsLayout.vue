<template>
  <div class="first-container">
    <v-container>
      <nar-bar :selected="'2'"></nar-bar>
    </v-container>
    <div class="main-container">
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
import axios from "axios";
// import Job from "../components/Job.vue";
export default {
  components: { NarBar, Job },
  props:['id'],
  mounted() {
    this.loadJobs();
  },
  data() {
    return {
      jobs: [],
      itemPerPage: 5,
      jobsToDisplay: [],
      pageCount: 1,
    };
  },
  methods: {
    loadJobs() {
        let id = this.$props.id;
      axios
        .get(`/api/get_company_positions/${id}`)
        .then((response) => {
          this.jobs = response.data;
          this.pageChanged(1);
          let pages = this.jobs.length / this.itemPerPage;
          if (pages % 1 > 0) pages = Math.round(pages);
          this.pageCount = pages;
        })
        .catch((err) => console.log(err));
    },
    pageChanged(page) {
      let firstIndex = (page - 1) * this.itemPerPage;
      let lastIndex = firstIndex + this.itemPerPage;
      if (lastIndex > this.jobs.length) lastIndex = this.jobs.length;
      this.jobsToDisplay = this.jobs.slice(firstIndex, lastIndex);
    },
  },
};
</script>
<style scoped>
.main-container {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
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