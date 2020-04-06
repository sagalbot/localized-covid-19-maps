<template>
  <div
    class="w-full h-full flex flex-col"
    :class="{ 'items-center justify-center': !hasRegions }"
  >
    <LineChart v-if="hasRegions" :options="options" :series="series" />
    <h4 v-else class="text-gray-600">
      Select some
      <inertia-link class="text-blue-600" href="/regions">regions</inertia-link>
      first to view a timeline chart.
    </h4>
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
    hasRegions() {
      return Boolean(this.$page.selectedRegions.length);
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
