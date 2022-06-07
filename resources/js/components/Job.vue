<template>
  <v-card class="ma-2 pa-2 deep-purple lighten-5 pointer" v-ripple elevation="2">
    <v-card-title style="word-break: break-word">
      {{ job.postion_name }}
    </v-card-title>
    <v-divider></v-divider>
    <v-row>
      <div class="purple--text div-50">Location:</div>
      <div class="orange--text div-50">
        {{ job.location }}
      </div>
    </v-row>
    <v-row>
      <div class="purple--text div-50">Job Role:</div>
      <div class="orange--text div-50">
        {{ job.job_role }}
      </div> </v-row
    ><v-row>
      <div class="purple--text div-50">Job Level:</div>
      <div class="orange--text div-50">
        {{ job.job_level }}
      </div> </v-row
    ><v-row>
      <div class="purple--text div-50">Experience:</div>
      <div class="orange--text div-50">{{ job.experience }} Years</div> </v-row
    ><v-row>
      <div class="purple--text div-50">Salary:</div>
      <div class="orange--text div-50">{{ job.salary }} S.P</div> </v-row
    ><v-row>
      <div class="purple--text div-50">Remotly:</div>
      <div class="orange--text div-50">
        {{ job.remote ? "Yes" : "No" }}
      </div>
    </v-row>
    <v-divider></v-divider
    ><v-row>
      <div class="purple--text div-50">Comapny Name:</div>
      <div class="orange--text div-50">
        {{ job.company.name }}
      </div>
    </v-row>
  </v-card>
</template>
<script>
export default {
  props: ["job"],
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
</style>