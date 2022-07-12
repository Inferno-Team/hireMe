<template>
  <div :style="computedStyle"></div>
</template>
<script>
export default {
  props: ["dim", "color", "rotate", "pos","name"],
  computed: {
    computedStyle() {
      return {
        background: `${this.$props.color}`, 
        height: `${this.$props.dim}px`, // 10px $x + "px" `${x}px`
        width: `${this.$props.dim}px`,
        "clip-path": "polygon(100% 51%, 0 0, 3% 100%)",
        transform: `rotate(${this.$props.rotate}deg)`,
        position: "absolute",
        left: `${this.$props.pos.left}px`,
        top: `${this.$props.pos.top}px`,
        "z-index": "1",
      };
    },
  },
};
</script>
