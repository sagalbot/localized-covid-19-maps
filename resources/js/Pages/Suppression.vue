<template>
  <div class="w-full h-full">
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

export default {
  name: 'Suppression',
  layout: SidebarLayout,
  components: { VueApexCharts },
  data: () => ({
    width: 0
  }),
  mounted() {
    this.width = this.$el.getBoundingClientRect().width;
  },
  computed: {
    options() {
      return {
        xaxis: {
          type: 'datetime'
        }
      };
    },
    series() {
      return this.$page.series.map(({ name, reports }) => ({
        name,
        data: reports.map(({ date, confirmed }, index) => ({
          x: index,
          y: confirmed
        }))
      }));
    }
  }
};
</script>

<style scoped></style>
