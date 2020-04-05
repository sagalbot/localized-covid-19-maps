<template>
  <div class="w-full h-full flex flex-col items-center justify-center">
    <LineChart :options="options" :series="series" />
  </div>
</template>

<script>
import SidebarLayout from '../Layout/SidebarLayout';
import { chartConfig } from '../utility/charts';
import LineChart from './LineChart';

export default {
  name: 'Timeline',
  layout: SidebarLayout,
  components: { SidebarLayout, LineChart },
  computed: {
    options() {
      return chartConfig({
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
