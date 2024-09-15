<template>
  <div class="graph-wrap">
    <div class="graph-container">
      <div class="win" :style="{ width: winningRate + '%' }">
        <div class="bar" :class="{ isAllWin: isAllWin }" />
      </div>
      <div class="lose" :style="{ width: losingRate + '%' }">
        <div class="bar" :class="{ isAllLose: isAllLose }" />
      </div>
      <div class="win-text">
        {{ wins }}
      </div>
      <div class="lose-text">
        {{ losses }}
      </div>
    </div>
    <div class="percentage">
      {{ winningRate + '%' }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    wins: Number,
    losses: Number
  },
  computed: {
    winningRate () {
      const total = this.wins + this.losses
      return Math.round((this.wins * 100) / total)
    },
    losingRate () {
      const total = this.wins + this.losses
      return 100 - Math.round((this.wins * 100) / total)
    },
    isAllWin () {
      return this.losses === 0 && this.wins > 0
    },
    isAllLose () {
      return this.wins === 0 && this.losses > 0
    }
  }
}
</script>

<style scoped>
@media (max-width: 992px) {
    .graph-wrap {
        display: flex;
        padding: 0;
        margin: 0 auto;
    }
}

@media (min-width: 992px) {
    .graph-wrap {
        display: flex;
        padding: 0 20px;
        margin: 0 auto;
    }
}

.graph-wrap .graph-container {
    width: calc(100% - 28px);
    display: flex;
    position: relative;
}

.graph-wrap .graph-container .win .bar {
    height: 20px;
    background-color: #3d95e5;
    border-radius: 3px 0 0 3px;
}

.graph-wrap .graph-container .win .bar.isAllWin {
    border-radius: 3px;
}

.graph-wrap .graph-container .win-text {
    position: absolute;
    top: 0;
    left: 6px;
    color: #fff;
    font-weight: bold;
}

.graph-wrap .graph-container .lose .bar {
    height: 20px;
    background-color: #ee5a52;
    border-radius: 0px 3px 3px 0px;
}

.graph-wrap .graph-container .lose .bar.isAllLose {
    border-radius: 3px;
}

.graph-wrap .graph-container .lose-text {
    position: absolute;
    top: 0;
    right: 6px;
    color: #fff;
    font-weight: bold;
}

.graph-wrap .percentage {
    width: 28px;
}
</style>
