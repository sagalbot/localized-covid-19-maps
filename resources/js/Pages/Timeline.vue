<template>
  <SidebarLayout>
    <div ref="container" class="w-full h-full">
      <VueApexCharts
        type="line"
        :width="width"
        :series="series"
        :options="options"
      />
    </div>
  </SidebarLayout>
</template>

<script>
import SidebarLayout from '../Layout/SidebarLayout';
import VueApexCharts from 'vue-apexcharts';

export default {
  name: 'Dashboard',
  components: { SidebarLayout, VueApexCharts },
  data: () => ({
    width: 0
  }),
  mounted() {
    this.width = this.$refs.container.getBoundingClientRect().width;
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
        data: reports.map(({ date, confirmed }) => ({
          x: date,
          y: confirmed
        }))
      }));
    }
  }
  // layout: SidebarLayout
};
</script>

<style scoped></style>
