<template>
  <div class="first-container">
    <v-container>
      <nar-bar :selected="'2'"></nar-bar>
    </v-container>
    <div class="main-container">
      <div class="side-bar">
        <div
          style="max-width: fit-content"
          class="
            purple--text
            text--darken-2
            font-weight-bold
            text-lg-h6
            ma-auto
          "
        >
          Filter
        </div>
        <v-btn text @click="clear" v-if="!isClear">clear</v-btn>
        <v-text-field
          class="input-field mt-4"
          label="Position Name"
          outlined
          @change="change"
          hint="Android Developer"
          v-model="sendData.postion_name"
          type="text"
          color="purple darken-3"
        ></v-text-field>
        <v-text-field
          class="input-field"
          label="Location"
          outlined
          hint="Aleppo"
          v-model="sendData.location"
          type="text"
          @change="change"
          color="purple darken-3"
        ></v-text-field>
        <v-text-field
          class="input-field"
          label="Years of Experience"
          outlined
          hint="1 Year"
          v-model="sendData.experience"
          type="number"
          @change="change"
          color="purple darken-3"
        ></v-text-field>
        <v-select
          :items="levels"
          label="Job Level"
          @change="change"
          outlined
          v-model="sendData.job_level"
        ></v-select>
        <v-range-slider
          v-model="value"
          step="10"
          ticks="always"
          tick-size="4"
          label="Salary"
          :hint="saleryHint"
          persistent-hint
          @change="salaryRangeChange"
        >
        </v-range-slider>
        <v-checkbox
          v-model="sendData.remotly"
          :label="'Remotly ?'"
          color="purple"
          @change="change"
        ></v-checkbox>
        <v-btn
          block
          :loading="applying"
          color="purple lighten-2"
          elevation="4"
          dark
          @click="applyFilter"
          >Apply</v-btn
        >
      </div>
      <div class="jobs-and-page">
        <div class="jobs">
          <Job
            v-for="(job, index) in jobsToDisplay"
            :key="index"
            :job="job"
            @onJobClicked="onJobClicked"
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
    this.loadJobs();
  },
  data() {
    return {
      value: [0, 10],
      salaries: [
        "100000",
        "150000",
        "200000",
        "300000",
        "400000",
        "500000",
        "600000",
        "800000",
        "1000000",
        "2000000",
        "3000000",
      ],
      saleryHint: "",
      sendData: {},
      levels: [
        "Internship",
        "Joiner",
        "Senior",
        "Team Leader",
        "Project Manager",
      ],
      applying: false,
      jobs: [],
      isClear: true,
      itemPerPage: 5,
      jobsToDisplay: [],
      pageCount: 1,
    };
  },
  methods: {
    salaryRangeChange(val) {
      let firstIndex = val[0] / 10;
      let secondIndex = val[1] / 10;
      let firstSalary = this.salaries[firstIndex];
      let secondSalary = this.salaries[secondIndex];
      this.saleryHint = `[ ${firstSalary} , ${secondSalary} ] S.P`;
      this.sendData.salary_low = firstSalary;
      this.sendData.salary_high = secondSalary;
      this.change(val);
    },
    applyFilter() {
      axios
        .post("/api/filter", this.sendData)
        .then((response) => {
          this.jobs = response.data;
          this.applying = false;
          this.pageChanged(1);
          let pages = this.jobs.length / this.itemPerPage;
          if (pages % 1 > 0) pages = Math.round(pages);
          this.pageCount = pages;
        })
        .catch((err) => {
          console.log(err);
          this.applying = false;
        });
      this.applying = true;
    },
    loadJobs() {
      axios
        .get("/api/get_positions")
        .then((response) => {
          this.jobs = response.data;
          this.pageChanged(1);
          this.pageCount = Math.ceil(this.jobs.length / this.itemPerPage);
        })
        .catch((err) => console.log(err));
    },
    pageChanged(page) {
      this.jobsToDisplay = [];
      let firstIndex = (page - 1) * this.itemPerPage;
      let lastIndex = firstIndex + this.itemPerPage;
      if (lastIndex > this.jobs.length) lastIndex = this.jobs.length;
      this.jobsToDisplay = this.jobs.slice(firstIndex, lastIndex);
    },
    change(val) {
      if (val) this.isClear = false;
      if (this.sendData != {}) this.isClear = false;
      else this.isClear = true;
    },
    clear() {
      this.sendData = {};
      this.isClear = true;
      this.value = [0, 10];
    },
    onJobClicked(id) {
      const type = localStorage.getItem("hire-me-user-type");
      if (type !== "user") return;
      this.selectedJobId = id;
      this.applyDialogState = true;
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
.side-bar {
  width: 20rem;
  padding: 1rem;
  background-color: #fafafa;
  border-right: thin solid #ccc;
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