<template>
  <div
    class="w-full h-full flex flex-col"
    :class="{ 'items-center justify-center': !hasRegions }"
  >
    <template v-if="hasRegions">
      <div class="px-4 py-2 mt-2">
        <h4 class="font-bold text-gray-500">Y-Axis Options</h4>
        <label class="mr-4">
          <input
            type="radio"
            v-model="yAxis"
            :value="yAxisOptions.TOTAL_CONFIRMED"
          />
          Total Confirmed Cases
        </label>
        <label>
          <input
            type="radio"
            v-model="yAxis"
            :value="yAxisOptions.CASES_PER_DAY"
          />
          New Cases / Day
        </label>
      </div>
      <LineChart :series="series" :options="chartOptions" />
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
    </template>
    <h4 v-else class="text-gray-600">
      Select some
      <inertia-link class="text-blue-600" href="/regions">regions</inertia-link>
      first to view a suppression chart.
    </h4>
  </div>
</template>

<script>
import SidebarLayout from '../Layout/SidebarLayout';
import LineChart from './LineChart';
import abbr from 'sugar/number/abbr';

const yAxisOptions = {
  CASES_PER_DAY: 'CASES_PER_DAY',
  TOTAL_CONFIRMED: 'TOTAL_CONFIRMED'
};

export default {
  name: 'Suppression',
  layout: SidebarLayout,
  components: { LineChart },
  data() {
    return {
      yAxis: yAxisOptions.TOTAL_CONFIRMED
    };
  },
  computed: {
    yAxisOptions: () => yAxisOptions,
    chartOptions() {
      return this.yAxis === this.yAxisOptions.CASES_PER_DAY
        ? {
            stroke: {
              curve: 'smooth'
            }
          }
        : {};
    },
    hasRegions() {
      return Boolean(this.$page.selectedRegions.length);
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
        data: reports.map(({ date, confirmed }, index) => {
          let y = confirmed;

          if (this.yAxis === this.yAxisOptions.CASES_PER_DAY) {
            const previous =
              index === 0 ? 100 : parseInt(reports[index - 1].confirmed);
            y = confirmed - previous;
          }

          return {
            x: index,
            y
          };
        })
      }));
    }
  },
  methods: {
    abbr
  }
};
</script>
