<template>
  <div>
    <v-container>
      <nar-bar :selected="'3'"></nar-bar>
    </v-container>
    <v-container>
      <div v-for="(_job, index) in jobs" :key="index">{{ _job.postion_name }}</div>
    </v-container>
  </div>
</template>
<script>
import NarBar from "../components/NarBar.vue";
export default {
  components: { NarBar },
  props: ["job"],
  data() {
    return {
      jobs: [],
    };
  },
  mounted() {
    this.jobs = JSON.parse(this.$props.job);
  },
};
</script>