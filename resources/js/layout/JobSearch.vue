<template>
  <div>
    <v-container>
      <nar-bar :selected="'3'"></nar-bar>
    </v-container>
    <v-container>
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
    </v-container>
  </div>
</template>
<script>
import NarBar from "../components/NarBar.vue";
import Job from "../components/Job.vue";
export default {
  components: { NarBar, Job },
  props: ["job"],
  data() {
    return {
      jobs: [],
      jobsToDisplay: [],
      itemPerPage: 6,
      pageCount: 1,
    };
  },
  mounted() {
    this.jobs = JSON.parse(this.$props.job);
    this.pageCount = Math.ceil(this.jobs.length / this.itemPerPage);
    this.pageChanged(1);
  },
  methods: {
    pageChanged(page) {
      this.jobsToDisplay = [];
      let firstIndex = (page - 1) * this.itemPerPage;
      let lastIndex = firstIndex + this.itemPerPage;
      if (lastIndex > this.jobs.length) lastIndex = this.jobs.length;
      this.jobsToDisplay = this.jobs.slice(firstIndex, lastIndex);
    },
  },
};
</script>

<style scoped>
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