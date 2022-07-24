<template>
  <div>
    <v-container>
      <nar-bar :selected="'4'"></nar-bar>
    </v-container>
    <v-container>
      <section>
        <div class="imgBx">
          <img src="/images/svg/svg1.svg" />
        </div>
        <div class="sectionBx">
          <div class="login-text">
            <h2>Log In</h2>
            <form @submit.prevent>
              <div class="input-fields">
                <v-text-field
                  class="input-field"
                  label="Email"
                  outlined
                  v-model="fields.email"
                  type="email"
                  :rules="[(value) => !!value || 'Required.']"
                  color="purple darken-3"
                ></v-text-field>
                <v-text-field
                  class="input-field"
                  label="Password"
                  required
                  v-model="fields.password"
                  :rules="[(value) => !!value || 'Required.']"
                  :append-icon="passState ? 'visibility' : 'visibility_off'"
                  @click:append="() => (passState = !passState)"
                  :type="passState ? 'password' : 'text'"
                  outlined
                  color="purple darken-3"
                ></v-text-field>
                <div class="login-signup">
                  <v-btn
                    elevation="2"
                    dark
                    rounded
                    class="orange darken-3"
                    style="margin: 0.2rem"
                    large
                    @click="login"
                    :loading="isLoading"
                    >Log In</v-btn
                  >
                  <div class="signup">
                    <div>don't have account yet ?</div>
                    <!-- <div class="deep-purple--text text--darken-4 text-h6">
                      Create
                    </div> -->
                    <v-btn text color="orange darken-3" @click="create"
                      >Create</v-btn
                    >
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>
    </v-container>
  </div>
</template>
<script>
import NarBar from "../components/NarBar.vue";
export default {
  components: { NarBar },
  mounted() {
    const token = localStorage.getItem("hire-me-token");
    if (token !== null && token !== undefined)
      this.$router.push({ name: "home" });
  },
  data() {
    return {
      fields: {},
      passState: Boolean,
      errors: [],
      isLoading: false,
    };
  },
  methods: {
    login() {
      this.isLoading = true;
      axios
        .post("/api/login", this.fields)
        .then((response) => {
          let res = response.data;
          if (res.status_code == 200) {
            localStorage.setItem("hire-me-token", res.token);
            localStorage.setItem("hire-me-user-type", res.type);
            axios.defaults.headers.common[
              "Authorization"
            ] = `Bearer ${res.token}`;
            this.$router.push({ name: "home" });
          } else {
            alert(res.message);
          }
          this.isLoading = false;
        })
        .catch((err) => {
          alert("there is something wrong please try again later.");
          this.isLoading = false;
          console.log(err);
        });
    },
    create() {
      this.$router.push({ name: "join_now" });
    },
  },
};
</script>
<style scoped>
* {
  box-sizing: border-box;
  overflow: hidden;
}

section {
  position: relative;
  width: 100%;
  height: 80vh;
  display: flex;
  direction: rtl;
}

section .imgBx {
  position: relative;
  width: 45%;
  height: 100%;
  box-shadow: 0px 0px 40px #ccc;
}

section .imgBx::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(225deg, #e91e63, #03a9fa);
  z-index: 1;
  mix-blend-mode: screen;
}

section .imgBx img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

section .sectionBx {
  width: 50%;
  direction: ltr;
  margin-top: 2rem;
}

section .sectionBx .login-text {
  width: 100%;
  text-align: center;
}
section .sectionBx .login-text h2 {
  /* color: #607d8b; */
  font-weight: 600;
  font-size: 1.6em;
  margin-bottom: 20px;
  border-bottom: 4px solid #ef6c00;
  display: inline-block;
  letter-spacing: 1px;
}
.input-fields {
  display: flex;
  align-items: center;
  flex-direction: column;
}
.input-fields .input-field {
  margin-top: 0.25rem;
  padding-top: 0.25rem;
  width: 22rem;
}
.login-signup {
  width: 22rem;
  display: flex;
  align-items: baseline;
}
.signup {
  display: flex;
  align-items: baseline;
  width: 100%;
  justify-content: space-evenly;
}
.signup div:first-child {
  font-size: 0.8rem;
}
.signup div:last-child {
  font-size: 1.2rem;
}
</style>