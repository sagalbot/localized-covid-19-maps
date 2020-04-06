<template>
  <div
    class="loading-spinner infinite-spin rounded-full border-2 border-blue-300"
    :style="{
      width: `${size}px`,
      height: `${size}px`
    }"
  >
    <span class="sr-only">Loading...</span>
  </div>
</template>

<script>
export default {
  props: {
    /**
     * Set the width and height in pixels.
     */
    size: {
      type: Number,
      default: 40
    }
  }
};
</script>
