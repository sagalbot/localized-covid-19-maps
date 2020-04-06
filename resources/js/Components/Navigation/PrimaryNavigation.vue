<template>
  <div class="h-0 flex-1 flex flex-col overflow-y-auto text-gray-700">
    <nav>
      <h4
        v-if="!collapsed"
        class="mt-10 border-b border-t border-gray-200 px-5 py-2 mb-2"
      >
        Regions
      </h4>
      <inertia-link
        :key="regions.route.toString()"
        :href="regions.route.toString() + queryString"
        :class="{
          'bg-blue-100 hover:text-blue-700': routeActive(regions.route.name)
        }"
        class="flex w-full inline-flex justify-between items-center px-4 py-2 mb-2 text-base text-gray-700 leading-6 rounded-md transition ease-in-out duration-150 hover:bg-blue-100 hover:text-blue-700"
      >
        <span v-if="!collapsed">{{ regions.route.name }}</span>
        <Icon :name="regions.icon" :size="4" />
      </inertia-link>

      <h4
        v-if="!collapsed"
        class="mt-10 border-b border-t border-gray-200 px-5 py-2 mb-2"
      >
        Reports
      </h4>
      <inertia-link
        v-for="{ route, icon } in reports"
        :key="route.toString()"
        :href="route.toString() + queryString"
        :class="{ 'bg-blue-100 text-blue-700': routeActive(route.name) }"
        class="flex w-full inline-flex justify-between items-center px-4 py-2 mb-2 text-base text-gray-700 leading-6 rounded-md transition ease-in-out duration-150 hover:bg-blue-100 hover:text-blue-700"
      >
        <span v-if="!collapsed">{{ route.name }}</span>
        <Icon :name="icon" :size="4" />
      </inertia-link>
    </nav>
  </div>
</template>

<script>
import { CALENDAR, GLOBE, LOCK_CLOSED } from '../Icons/icons';
import Icon from '../Icons/Icon.vue';

export default {
  components: { Icon },
  props: {
    collapsed: {
      type: Boolean,
      default: true
    }
  },
  computed: {
    reports() {
      return [
        { route: this.route('timeline'), icon: CALENDAR },
        { route: this.route('suppression'), icon: LOCK_CLOSED }
      ];
    },
    regions() {
      return { route: this.route('regions'), icon: GLOBE };
    },
    queryString() {
      return !!this.$page.selectedRegions.length
        ? '?regions=' + btoa(JSON.stringify(this.$page.selectedRegions))
        : '';
    }
  }
};
</script>
