<template>
  <main class="container" style="margin-top:10px;">
    <div class="row justify-content-center">
      <div class="col-xl-8 col-lg-8 col-md-8">
        <div id="canvas-holder" style="width:100%; ">
          <LineChart />
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import LineChart from '../../components/LineChart.vue'

export default {
  components: {
    LineChart
  },
  layout: 'front_gray'
}
</script>

<style scoped>
.container {
    flex: 1 0 auto;
}
</style>
