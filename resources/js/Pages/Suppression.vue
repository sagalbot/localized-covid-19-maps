<template>
  <div class="w-full h-full flex flex-col items-center justify-center">
    <VueApexCharts
      type="line"
      :width="width"
      :series="series"
      :options="options"
      class="py-5 mb-5"
    />
    <h1 class="text-xl text-gray-700">Days Since 100th Case</h1>

    <div class="grid grid-cols-4 gap-3 py-5">
      <div
        v-for="region in hundredthCaseDays"
        :key="region.name"
        class="border-2 rounded mx-4 p-4 text-center"
      >
        <h2 class="font-bold text-lg" :style="{ color: region.color }">
          {{ region.name }}
        </h2>
        <h5 class="text-xl">{{ region.duration }} days</h5>
        <h5 class="text-sm">{{ abbr(region.current.confirmed, 1) }} cases</h5>
      </div>
    </div>
  </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts';
import abbr from 'sugar/number/abbr';
import SidebarLayout from '../Layout/SidebarLayout';
import { chartConfig } from '../utility/charts';
import { chartColors } from '../utility/colors';

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
    chartColors,
    options() {
      return chartConfig({
        height: this.width,
        colors: this.chartColors
      });
    },
    hundredthCaseDays() {
      return this.$page.series
        .filter(({ reports }) => reports.length)
        .map(({ name, reports }, index) => ({
          name,
          date: new Date(reports[0].date),
          current: reports[reports.length - 1],
          duration: reports.length
          // color: this.chartColors[index]
        }))
        .sort((a, b) => a.duration < b.duration);
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
  },
  methods: {
    abbr
  }
};
</script>

<style scoped></style>
