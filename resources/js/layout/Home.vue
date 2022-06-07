<template>
  <div>
    <v-container>
      <nar-bar :selected="'1'"></nar-bar>
      <div
        style="
          margin-top: 0.5rem;
          display: flex;
          flex-direction: column;
          align-items: center;
        "
      >
        <div
          class="indigo--text darken-4 text-h4 font-weight-black"
          style="z-index: 2"
        >
          The Most Exciting Jobs
        </div>
        <div
          style="margin-top: 0.5rem; z-index: 2"
          class="grey--text text--lighten-1 text-h6"
        >
          SEARCH JOB HERE
        </div>
        <home-search-card
          style="margin-top: 0.5rem; z-index: 4"
        ></home-search-card>
      </div>
    </v-container>
    <div class="bottom_image_container" style="z-index: 1">
      <div class="circle_image">
        <img src="/images/home_avatar.jpg" />
      </div>
      <img src="/images/home_bottom_image.png" />
    </div>
    <triangle-item
      v-for="(shape, index) in this.shapes"
      :key="index"
      :color="shape.color"
      :dim="shape.dim"
      :pos="shape.pos"
      :rotate="shape.deg"
    />
  </div>
</template>
<script>
import HomeSearchCard from "../components/HomeSearchCard.vue";
import TriangleItem from "../components/TriangleItem.vue";
import NarBar from "../components/NarBar.vue";
export default {
  components: { HomeSearchCard, TriangleItem, NarBar },
  mounted() {
    this.generateShapes();
  },
  methods: {
    randomInt(min, max) {
      // min and max included
      return Math.floor(Math.random() * (max - min + 1) + min);
    },
    generateShapes() {
      for (let index = 0; index < 10; index++) {
        const red = this.randomInt(0, 255);
        const green = this.randomInt(0, 255);
        const blue = this.randomInt(0, 255);
        const deg = this.randomInt(0, 180);
        const dim = this.randomInt(30, 80);

        const width = window.screen.width;
        const height = window.screen.height;
        let left = this.randomInt(width * 0.1, width * 0.85);
        while (left > width * 0.25 && left < width * 0.75)
          left = this.randomInt(width * 0.1, width * 0.85);
        const top = this.randomInt(height * 0.1, height * 0.9);
        this.shapes.push({
          color: `rgb(${red},${green},${blue})`,
          deg: deg,
          dim: dim,
          pos: {
            left: left,
            top: top,
          },
        });
      }
    },
  },
  data() {
    return {
      shapes: [],
    };
  },
};
</script>
<style scoped>
.bottom_image_container {
  display: flex;
  align-items: flex-end;
  position: relative;
  margin-top: 3.25rem;
}

.bottom_image_container img {
  width: 100%;
  z-index: 3;
  height: 275px;
}
.circle_image {
  position: absolute;
  overflow: hidden;
  left: 50%;
  top: 30%;
  transform: translate(-50%, -50%);
  height: 250px;
  width: 500px;
  z-index: 1;
  border-radius: 250px 250px 0 0;
  background-color: #fed1d4;
}
</style>