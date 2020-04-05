<template>
  <div class="w-full h-full flex flex-col items-center justify-center">
    <VueApexCharts
      type="line"
      :width="width"
      :series="series"
      :options="options"
    />
  </div>
</template>

<script>
import SidebarLayout from '../Layout/SidebarLayout';
import VueApexCharts from 'vue-apexcharts';
import { chartConfig } from '../utility/charts';

export default {
  name: 'Timeline',
  layout: SidebarLayout,
  components: { SidebarLayout, VueApexCharts },
  data: () => ({
    width: 0
  }),
  mounted() {
    this.width = this.$el.getBoundingClientRect().width;
  },
  computed: {
    options() {
      return chartConfig({
        width: this.width,
        xaxis: {
          type: 'datetime'
        }
      });
    },
    series() {
      return this.$page.series.map(({ name, reports }) => ({
        name,
        data: reports.map(({ date, confirmed }) => ({
          x: date,
          y: confirmed
        }))
      }));
    }
  }
};
</script>

<style scoped></style>
