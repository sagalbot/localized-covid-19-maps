<template>
  <div
    ref="chartContainer"
    class="w-full flex flex-col pt-3 pr-5 chart-container"
  >
    <div class="w-full flex-1">
      <VueApexCharts
        type="line"
        height="100%"
        width="100%"
        :series="series"
        :options="chartConfig"
        :key="componentKey"
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
      type: Object,
      default: () => {}
    },
    series: {
      required: true,
      type: Array
    }
  },
  data: () => ({
    resizeObserver: {},
    componentKey: 0
  }),
  computed: {
    chartConfig() {
      return chartConfig(this.options);
    }
  },
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
        this.componentKey += 1;
      });
    }
  }
};
</script>
