<template>
  <div
    class="w-full h-full flex flex-col"
    :class="{ 'items-center justify-center': !hasRegions }"
  >
    <template v-if="hasRegions">
      <LineChart :series="series" />
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

export default {
  name: 'Suppression',
  layout: SidebarLayout,
  components: { LineChart },
  computed: {
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
