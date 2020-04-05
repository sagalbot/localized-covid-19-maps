<template>
  <div class="w-full h-full flex flex-col">
    <div class="w-full flex justify-center items-center flex-col pt-3 pr-5">
      <div ref="chartContainer" class="w-full">
        <VueApexCharts
          type="line"
          :width="width"
          :series="series"
          :options="options"
        />
      </div>
    </div>
    <h1 class="text-xl text-gray-700 pl-5">Days Since 100th Case</h1>

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
    width: 0,
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
  computed: {
    chartColors,
    options() {
      return chartConfig();
    },
    hundredthCaseDays() {
      return this.$page.series
        .filter(({ reports }) => reports.length)
        .map(({ name, reports }, index) => ({
          name,
          date: new Date(reports[0].date),
          current: reports[reports.length - 1],
          duration: reports.length
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
  // watch: {
  //   width(current, prev) {
  //     if (prev !== 0) {
  //       this.$forceUpdate();
  //     }
  //   }
  // },
  methods: {
    abbr,
    onResize() {
      window.requestAnimationFrame(() => {
        this.width = this.$refs.chartContainer.getBoundingClientRect().width;
      });
    }
  }
};
</script>

<style scoped></style>
