<template>
  <v-speed-dial
    v-model="fab"
    :direction="'right'"
    :open-on-hover="hover"
    :transition="'scale-transition'"
  >
    <template v-slot:activator>
      <v-card
        :style="mine"
        v-longclick="onLongClick"
        @click.prevent="oninnerJobClicked"
        :class="[
          'ma-2',
          'pa-2',
          'deep-purple',
          isMineObject ? 'no-pointer' : 'pointer',
          isMineObject ? 'lighten-3' : 'lighten-5',
        ]"
        v-ripple="!isMineObject"
        elevation="2"
      >
        <v-card-title style="word-break: break-word">
          {{ innerJob.postion_name }}
        </v-card-title>
        <v-divider></v-divider>
        <v-row>
          <div class="purple--text div-50">Location:</div>
          <div
            :class="[
              isMineObject ? 'text--lighten-5' : '',
              'orange--text',
              'div-50',
            ]"
          >
            {{ innerJob.location }}
          </div>
        </v-row>
        <v-row>
          <div class="purple--text div-50">Job Role:</div>
          <div
            :class="[
              isMineObject ? 'text--lighten-5' : '',
              'orange--text',
              'div-50',
            ]"
          >
            {{ innerJob.job_role }}
          </div> </v-row
        ><v-row>
          <div class="purple--text div-50">Job Level:</div>
          <div
            :class="[
              isMineObject ? 'text--lighten-5' : '',
              'orange--text',
              'div-50',
            ]"
          >
            {{ innerJob.job_level }}
          </div> </v-row
        ><v-row>
          <div class="purple--text div-50">Experience:</div>
          <div
            :class="[
              isMineObject ? 'text--lighten-5' : '',
              'orange--text',
              'div-50',
            ]"
          >
            {{ innerJob.experience }} Years
          </div> </v-row
        ><v-row>
          <div class="purple--text div-50">Salary:</div>
          <div
            :class="[
              isMineObject ? 'text--lighten-5' : '',
              'orange--text',
              'div-50',
            ]"
          >
            {{ innerJob.salary }} S.P
          </div> </v-row
        ><v-row>
          <div class="purple--text div-50">Remotly:</div>
          <div
            :class="[
              isMineObject ? 'text--lighten-5' : '',
              'orange--text',
              'div-50',
            ]"
          >
            {{ innerJob.remote ? "Yes" : "No" }}
          </div>
        </v-row>
        <v-divider></v-divider
        ><v-row>
          <div class="purple--text div-50">Comapny Name:</div>
          <div
            :class="[
              isMineObject ? 'text--lighten-5' : '',
              'orange--text',
              'div-50',
            ]"
          >
            {{ innerJob.company.name }}
          </div>
        </v-row>
        <v-dialog v-model="applyDialogState" max-width="290">
          <v-card>
            <v-card-title class="text-h5">
              Applying to this Job ?
            </v-card-title>
            <div class="text-center" v-if="isloadingApply">
              <v-progress-circular
                indeterminate
                color="success"
                class="m-auto"
              ></v-progress-circular>
            </div>

            <v-file-input
              accept="application/pdf"
              label="CV File Only PDF allowed"
              chips
              v-if="!isloadingApply"
              outlined
              @change="getFileObject($event)"
              show-size
              clearable
              v-model="filename"
              style="width: 275px"
              truncate-length="10"
            ></v-file-input>
            <v-card-actions>
              <v-spacer></v-spacer>

              <v-btn
                color="green darken-1"
                text
                @click="applyDialogState = false"
              >
                Cancel
              </v-btn>

              <v-btn color="green darken-1" text @click="applyForJob">
                Apply
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog v-model="deleteDialogState" max-width="290">
          <v-card>
            <v-card-title class="text-h5">
              Are you sure you want to delete this job ?
            </v-card-title>
            <div class="text-center" v-if="isloadingApply">
              <v-progress-circular
                indeterminate
                color="success"
                class="m-auto"
              ></v-progress-circular>
            </div>

            <v-card-actions>
              <v-spacer></v-spacer>

              <v-btn
                color="green darken-1"
                text
                @click="deleteDialogState = false"
              >
                No
              </v-btn>

              <v-btn color="green darken-1" text @click="deleteThisJob">
                Yes
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog
          fullscreen
          v-model="editDialogState"
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
              <v-btn icon dark @click="editDialogState = false"
                ><v-icon>mdi-close</v-icon></v-btn
              >
              <v-spacer></v-spacer>
              <v-toolbar-title>Edit Position</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-toolbar-items>
                <v-btn dark text @click="editPosition"> Save </v-btn>
              </v-toolbar-items>
            </v-toolbar>

            <div class="input-fields my-auto">
              <v-row>
                <v-col>
                  <v-text-field
                    class="input-field"
                    label="Position Name"
                    required
                    v-model="innerJob.postion_name"
                    :rules="[(value) => !!value || 'Required.']"
                    :type="'text'"
                    outlined
                    color="purple darken-3"
                  ></v-text-field>

                  <v-text-field
                    class="input-field"
                    label="Location"
                    required
                    v-model="innerJob.location"
                    :rules="[(value) => !!value || 'Required.']"
                    :type="'text'"
                    outlined
                    color="purple darken-3"
                  ></v-text-field>

                  <v-text-field
                    class="input-field"
                    label="Experience"
                    required
                    v-model="innerJob.experience"
                    :rules="[
                      (value) => !!value || 'Required.',
                      (value) =>
                        /^\d+$/.test(value) ||
                        'Please enter a positive number.',
                    ]"
                    :type="'number'"
                    outlined
                    color="purple darken-3"
                  ></v-text-field>

                  <v-checkbox
                    v-model="innerJob.remote"
                    :label="'Remotly ?'"
                    color="purple"
                  ></v-checkbox>
                </v-col>
                <v-col>
                  <v-text-field
                    class="input-field"
                    label="Job Role"
                    required
                    v-model="innerJob.job_role"
                    :rules="[(value) => !!value || 'Required.']"
                    :type="'text'"
                    outlined
                    color="purple darken-3"
                  ></v-text-field>
                  <v-select
                    :items="levels"
                    label="Job Level"
                    outlined
                    v-model="innerJob.job_level"
                  ></v-select>

                  <v-text-field
                    class="input-field"
                    label="Salary"
                    required
                    v-model="innerJob.salary"
                    :rules="[
                      (value) => !!value || 'Required.',
                      (value) =>
                        /^\d+$/.test(value) ||
                        'Please enter a positive number.',
                    ]"
                    :type="'number'"
                    outlined
                    color="purple darken-3"
                  ></v-text-field>
                </v-col>
              </v-row>
            </div>
          </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar" :timeout="1500">
          You are already applyed for this Job
        </v-snackbar>
      </v-card>
    </template>
    <v-btn
      @click="() => (editDialogState = true)"
      fab
      dark
      small
      color="purple lighten-1"
      :class="[hover ? '' : 'displayNone']"
    >
      <v-icon>mdi-pencil</v-icon>
    </v-btn>
  </v-speed-dial>
</template>
<script>
export default {
  props: ["job", "editable", "is_mine"],
  data() {
    return {
      isMineObject: false,
      fab: false,
      filename: null,
      hover: false,
      applyDialogState: false,
      cvFile: File,
      isloadingApply: false,
      snackbar: false,
      editDialogState: false,
      innerJob: null,
      deleteDialogState: false,
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
    oninnerJobClicked() {
      if (this.deleteDialogState) return;
      if (this.editable) {
        // move to cv's page
        this.$router.push({
          name: "applications",
          params: {
            id: this.job.id,
          },
        });
      } else {
        if (!this.isMineObject) {
          this.applyDialogState = true;
        } else this.snackbar = true;
      }
    },
    applyForJob() {
      this.isloadingApply = true;
      let formData = new FormData();
      formData.append("cv", this.cvFile);
      formData.append("id", this.innerJob.id);
      axios
        .post("/api/apply-to-job", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        })
        .then((result) => {
          if (result.data.code == 200) {
            this.applyDialogState = false;
            this.cvFile = null;
            this.filename = null;
            this.isMineObject = true;
            this.isloadingApply = false;
          } else alert(result.data.msg);
        })
        .catch((err) => {
          console.log(err);
          this.applyDialogState = false;
          this.isloadingApply = false;
        });
    },
    async getFileObject(file) {
      this.cvFile = await file;
    },
    editPosition() {
      this.editDialogState = false;
      axios
        .post("/api/edit_position", this.innerJob)
        .then((result) => {
          if (result.data.code == 200) {
            this.innerJob = result.data.position;
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    deleteThisJob() {
      this.isloadingApply = true;
      axios
        .post("/api/del_position", {
          id: this.job.id,
        })
        .then((result) => {
          if (result.data.code == 200) {
            this.deleteDialogState = false;
            this.$emit("del", this.job);
          } else alert(result.data.msg);
        })
        .catch((err) => {
          console.log(err);
          this.deleteDialogState = false;
          this.isloadingApply = false;
        });
    },
    onLongClick() {
      if (this.editable) this.deleteDialogState = true;
    },
  },
  computed: {
    mine() {
      this.isMineObject = this.$props.is_mine;
      this.hover = this.editable;
      this.innerJob = this.job;
      var apps = this.job.applications;
      if (apps == null || apps == undefined) {
        return;
      }
      for (var i = 0; i < apps.length; i++) {
        var app = apps[i];
        if (app.isMine) {
          this.isMineObject = true;
          break;
        }
      }
    },
  },
};
</script>
<style scoped>
.row {
  margin: 0px;
  padding: 16px;
}
.div-50 {
  width: 50%;
  font-size: 0.875rem;
}
.pointer {
  cursor: pointer;
}
.no-pointer {
  cursor: unset;
}
.displayNone {
  display: none !important;
}
</style>