<template>
    <div @click="click" style="cursor: pointer; transition: 0.5s; margin: 1rem;" :class="['font-weight-medium ',
    enabled ? 'underline' : '',
    enabled ? 'purple--text' : 'black--text']">
        {{ text }}
    </div>
</template>
<script>
export default {
    props: ['id', 'text', 'enabled'],
    methods: {
        click() { this.$emit('onTextClicked', this.$props.id) }
    }
}
</script>
<style scoped>
.underline {
    border-bottom: thick solid purple;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;

}
</style>