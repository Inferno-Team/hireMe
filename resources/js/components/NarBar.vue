<template>
  <v-row style="z-index: 3">
    <img
      src="/images/logo.png"
      class="mr-3"
      height="80"
      style="z-index: 3; margin-right: 2rem !important"
    />
    <v-row>
      <styled-text
        style="z-index: 3"
        :id="'1'"
        :enabled="textEnabledId == 1"
        :text="'Home'"
        @onTextClicked="textClicked"
      />
      <styled-text
        style="z-index: 3"
        :id="'2'"
        :enabled="textEnabledId == 2"
        :text="'Browse Jobs'"
        @onTextClicked="textClicked"
      />
      <styled-text
        style="z-index: 3"
        :id="'3'"
        :enabled="textEnabledId == 3"
        :text="'Companies'"
        @onTextClicked="textClicked"
      />
    </v-row>
    <v-row justify="end" style="z-index: 3">
      <home-button
        :color="'white'"
        :title="'Log In'"
        :id="'login'"
        v-if="!isLoggedIn"
        @pressed="login"
        style="z-index: 3"
      ></home-button>
      <home-button
        :color="'white'"
        :title="'Dashboard'"
        :id="'dashboard'"
        v-if="userType == 'admin'"
        style="z-index: 3"
      ></home-button>
      <home-button
        :color="'white'"
        :title="'My Company'"
        :id="'company'"
        v-if="userType == 'manager'"
        style="z-index: 3"
      ></home-button>
      <home-button
        :color="'white'"
        :title="'My Account'"
        :id="'company'"
        v-if="userType == 'user'"
        style="z-index: 3"
      ></home-button>

      <home-button
        :color="'purple'"
        :title="isLoggedIn ? 'logout' : 'Join Now'"
        :id="'join'"
        :loading="loading"
        @pressed="logoutOrJoin"
        style="z-index: 3"
      ></home-button>
    </v-row>
  </v-row>
</template>
<script>
import StyledText from "../components/StyledText.vue";
import HomeButton from "../components/HomeButton.vue";

export default {
  components: { HomeButton, StyledText },
  props: ["selected"],
  mounted() {
    let token = localStorage.getItem("hire-me-token");
    this.isLoggedIn = token !== null && token !== undefined;
    if (this.isLoggedIn)
      this.userType = localStorage.getItem("hire-me-user-type");
  },
  data() {
    return {
      textEnabledId: this.$props.selected,
      isLoggedIn: Boolean,
      userType: "",
      loading: false,
    };
  },
  methods: {
    textClicked(id) {
      this.textEnabledId = id;
      switch (id) {
        case "1":
          this.$router.push({ name: "home" });
          break;
        case "2":
          this.$router.push({ name: "jobs" });
          break;
        case "3":
          this.$router.push({ name: "companies" });
          break;
      }
    },
    login() {
      this.$router.push({ name: "login" });
    },

    logoutOrJoin() {
      if (this.isLoggedIn) {
        // logout
        this.loading = true;

        axios
          .post("/api/logout")
          .then((response) => {
            if (response.data.code == 200) {
              this.loading = false;
              localStorage.removeItem("hire-me-token");
              localStorage.removeItem("hire-me-user-type");
              if (this.$route.path == "/") location.reload();
              else this.$router.push({ name: "home" });
            }
          })
          .catch((err) => {
            alert("can't logout now please try again later.");
            console.log(err);
          });
      } else {
        this.$router.push({ name: "join_now" });
      }
    },
  },
};
</script>
<style scoped>
.row {
  align-items: center;
  margin: 0px;
}
</style>