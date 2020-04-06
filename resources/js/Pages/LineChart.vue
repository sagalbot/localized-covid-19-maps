<template>
  <div
    class="w-full flex justify-center items-center flex-col pt-3 pr-5 chart-container"
  >
    <div ref="chartContainer" class="w-full h-full">
      <VueApexCharts
        type="line"
        height="100%"
        :width="width"
        :series="series"
        :options="options"
      />
    </div>
  </div>
</template>
<script>
import VueApexCharts from 'vue-apexcharts';
import { chartConfig } from '../utility/charts';

export default {
  name: 'LineChart',
  components: { VueApexCharts },
  props: {
    options: {
      default: () => chartConfig()
    },
    series: {
      required: true,
      type: Array
    }
  },
  data: () => ({
    width: 0,
    height: 0,
    resizeObserver: {}
  }),
  mounted() {
    this.onResize();
    this.resizeObserver = new ResizeObserver(this.onResize);
    this.resizeObserver.observe(this.$refs.chartContainer, {
      box: 'border-box'
    });
  },
  beforeDestroy() {
    this.resizeObserver.unobserve(this.$refs.chartContainer);
  },
  methods: {
    onResize() {
      window.requestAnimationFrame(() => {
        const {
          height,
          width
        } = this.$refs.chartContainer.getBoundingClientRect();
        this.width = width;
        this.height = height;
      });
    }
  }
};
</script>
