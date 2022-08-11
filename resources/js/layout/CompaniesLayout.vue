<template>
  <div>
    <v-container>
      <nar-bar :selected="'3'"></nar-bar>
    </v-container>
    <v-container>
      <div>
        <div class="companys">
          <Company
            v-for="(company, index) in companiesToDisplay"
            :key="index"
            :company="company"
          ></Company>
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
import Company from "../components/Company.vue";
export default {
  components: { NarBar, Company },
  data() {
    return {
      companies: [],
      pageCount: 1,
      itemPerPage: 5,
      companiesToDisplay: [],
    };
  },
  mounted() {
    let token = localStorage.getItem("hire-me-token");
    let isLoggedIn = token !== null && token !== undefined;
    if (!isLoggedIn) {
      alert("You must login to see this page");
      this.$router.push({ name: "login" });
    } else
      axios
        .get("/api/get_all_companies")
        .then((response) => {
          this.companies = response.data;
          this.pageChanged(1);
          let pages = this.companies.length / this.itemPerPage;
          if (pages % 1 > 0) pages = Math.round(pages);
          this.pageCount = pages;
        })
        .catch((error) => {
          console.log(error);
        });
  },
  methods: {
    pageChanged(page) {
      let firstIndex = (page - 1) * this.itemPerPage;
      let lastIndex = firstIndex + this.itemPerPage;
      if (lastIndex > this.companies.length) lastIndex = this.companies.length;
      this.companiesToDisplay = this.companies.slice(firstIndex, lastIndex);
    },
  },
};
</script>
<style scoped>
.companys {
  display: grid;
  grid-template-columns: repeat(3, minmax(92px, 0.5fr));
  justify-items: center;
  gap: 10px;
  width: 100%;
  margin: 1rem;
}
</style>