<template>
  <div id="loding-icon" :class="{ isProcessing: isProcessing }" class="spinner-border text-warning" />
</template>

<script>
export default {
  props: {
    isProcessing: Boolean
  }
}
</script>

<style>
#loding-icon {
    display: none;
    position: fixed;
    left: 50%;
    top: 50%;
}

.isProcessing {
    display: inline-block !important;
}
</style>
