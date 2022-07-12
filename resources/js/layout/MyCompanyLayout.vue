<template>
  <div style="height: 100%">
    <v-container>
      <nar-bar :selected="'-'"></nar-bar>
    </v-container>
    <div :class="[loading ? 'default-container' : 'main-container']">
      <div v-if="loading" class="text-center" style="line-height: 33">
        <v-progress-circular
          indeterminate
          color="primary"
          class="m-auto"
        ></v-progress-circular>
      </div>
      <div class="jobs-and-page" v-if="jobs.length > 0">
        <div class="jobs">
          <Job
            v-for="(job, index) in jobsToDisplay"
            :key="index"
            :job="job"
            :editable="'true'"
            @del="onDeleted"
          ></Job>
        </div>
        <v-pagination
          class="card-footer pb-4 pt-3"
          :length="pageCount"
          @input="pageChanged"
        ></v-pagination>
      </div>
      <div v-if="!loading && jobs.length == 0" class="ma-auto">
        <div
          v-if="code == 200"
          style="line-height: 20"
          class="text-center text-lg-h6"
        >
          There is no position to display yet.
        </div>
        <div v-else class="text-lg-h6 text-center mt-auto">
          You don't have a company on our system yet<br />
          Please add new one and try again.
        </div>
      </div>
      <div class="ms-auto me-3 my-6">
        <home-button
          :color="'purple'"
          :title="'Add new Company'"
          :id="'x'"
          :loading="addNewCompanyLoading"
          @pressed="addCompanyDialog = true"
          v-if="!loading && code > 200"
          style="z-index: 3"
        ></home-button>

        <home-button
          :color="'purple'"
          :title="'Add new Position'"
          :id="'x'"
          :loading="addNewPosition"
          @pressed="addPositionDialog = true"
          v-if="!loading && code == 200"
          style="z-index: 3"
        ></home-button>
      </div>
    </div>
    <v-dialog
      fullscreen
      v-model="addCompanyDialog"
      hide-overlay
      transition="dialog-bottom-transition"
      scrollable
    >
      <v-card tile>
        <v-toolbar
          style="flex: 0 !important"
          :extended="false"
          flat
          dark
          color="purple"
        >
          <v-btn icon dark @click="addCompanyDialog = false"
            ><v-icon>mdi-close</v-icon></v-btn
          >
          <v-spacer></v-spacer>
          <v-toolbar-title>Add new Company</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark text @click="addCompany"> Save </v-btn>
          </v-toolbar-items>
        </v-toolbar>

        <div class="input-fields my-auto">
          <v-text-field
            class="input-field"
            label="Company Name"
            required
            v-model="company.name"
            :rules="[(value) => !!value || 'Required.']"
            :type="'text'"
            outlined
            color="purple darken-3"
          ></v-text-field>
          <v-file-input
            accept="image/*"
            label="Company Logo"
            chips
            outlined
            prepend-icon="mdi-camera"
            @change="getFileObject($event)"
            show-size
            style="width: 22rem"
            truncate-length="15"
          ></v-file-input>
          <v-text-field
            class="input-field"
            label="Company Description"
            required
            v-model="company.description"
            :rules="[(value) => !!value || 'Required.']"
            :type="'text'"
            outlined
            color="purple darken-3"
          ></v-text-field>
        </div>
      </v-card>
    </v-dialog>

    <v-dialog
      fullscreen
      v-model="addPositionDialog"
      hide-overlay
      transition="dialog-bottom-transition"
      scrollable
    >
      <v-card tile>
        <v-toolbar
          style="flex: 0 !important"
          :extended="false"
          flat
          dark
          color="purple"
        >
          <v-btn icon dark @click="addPositionDialog = false"
            ><v-icon>mdi-close</v-icon></v-btn
          >
          <v-spacer></v-spacer>
          <v-toolbar-title>Add new Position</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark text @click="addPosition"> Save </v-btn>
          </v-toolbar-items>
        </v-toolbar>

        <div class="input-fields my-auto">
          <v-row>
            <v-col>
              <v-text-field
                class="input-field"
                label="Position Name"
                required
                v-model="position.postion_name"
                :rules="[(value) => !!value || 'Required.']"
                :type="'text'"
                outlined
                color="purple darken-3"
              ></v-text-field>

              <v-text-field
                class="input-field"
                label="Location"
                required
                v-model="position.location"
                :rules="[(value) => !!value || 'Required.']"
                :type="'text'"
                outlined
                color="purple darken-3"
              ></v-text-field>

              <v-text-field
                class="input-field"
                label="Experience"
                required
                v-model="position.experience"
                :rules="[(value) => !!value || 'Required.']"
                :type="'number'"
                outlined
                color="purple darken-3"
              ></v-text-field>

              <v-checkbox
                v-model="position.remote"
                :label="'Remotly ?'"
                color="purple"
              ></v-checkbox>
            </v-col>
            <v-col>
              <v-text-field
                class="input-field"
                label="Job Role"
                required
                v-model="position.job_role"
                :rules="[(value) => !!value || 'Required.']"
                :type="'text'"
                outlined
                color="purple darken-3"
              ></v-text-field>
              <v-select
                :items="levels"
                label="Job Level"
                outlined
                v-model="position.job_level"
              ></v-select>

              <v-text-field
                class="input-field"
                label="Salary"
                required
                v-model="position.salary"
                :rules="[(value) => !!value || 'Required.']"
                :type="'number'"
                outlined
                color="purple darken-3"
              ></v-text-field>
            </v-col>
          </v-row>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import NarBar from "../components/NarBar.vue";
import Job from "../components/Job.vue";
import axios from "axios";
import HomeButton from "../components/HomeButton.vue";
// import Job from "../components/Job.vue";
export default {
  components: { NarBar, Job, HomeButton },
  mounted() {
    this.loadJobs();
  },
  data() {
    return {
      jobs: [],
      itemPerPage: 5,
      jobsToDisplay: [],
      pageCount: 1,
      code: Number,
      loading: true,
      addNewCompanyLoading: false,
      addNewPosition: false,
      addCompanyDialog: false,
      addPositionDialog: false,
      company: {},
      position: {
        remote: false,
      },
      levels: [
        "Internship",
        "Joiner",
        "Senior",
        "Team Leader",
        "Project Manager",
      ],
    };
  },
  methods: {
    loadJobs() {
      this.loading = true;
      axios
        .get(`/api/get_my_company_positions/`)
        .then((response) => {
          this.loading = false;
          var data = response.data;
          this.code = data.code;
          if (data.code == 200) {
            this.jobs = data.pos;
            this.pageChanged(1);
            this.pageCount = Math.ceil(this.jobs.length / this.itemPerPage);
          }
        })
        .catch((err) => console.log(err));
    },
    pageChanged(page) {
      let firstIndex = (page - 1) * this.itemPerPage;
      let lastIndex = firstIndex + this.itemPerPage;
      if (lastIndex > this.jobs.length) lastIndex = this.jobs.length;
      this.jobsToDisplay = this.jobs.slice(firstIndex, lastIndex);
    },

    addCompany() {
      this.addCompanyDialog = false;
      this.addNewCompanyLoading = true;
      let formData = new FormData();
      formData.append("logo", this.company.logo);
      formData.append("name", this.company.name);
      formData.append("description", this.company.description);

      axios
        .post("/api/add_company", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          let data = response.data;
          this.addNewCompanyLoading = false;
          this.code = 200;
          console.log(data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    async getFileObject(file) {
      this.company.logo = await file;
    },
    addPosition() {
      this.addNewPosition = true;
      this.addPositionDialog = false;
      axios
        .post("/api/add_position", this.position)
        .then((response) => {
          console.log(response.status);
          this.addNewPosition = false;
          if (response.data.code == 200) {
            this.jobs.unshift(response.data.position);
            this.pageChanged(1);
          }
        })
        .catch((err) => {
          this.addNewPosition = false;
        });
    },
    onDeleted(job) {
      this.loadJobs();
    },
  },
};
</script>
<style scoped>
.default-container {
  height: 100%;
}

.main-container {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: calc(100% - 104px) !important;
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
.input-fields {
  display: flex;
  align-items: center;
  flex-direction: column;
  /* height: 36rem; */
}
.input-fields .input-field {
  margin-top: 0.25rem;
  padding-top: 0.25rem;
  width: 22rem;
}
</style>