<template>
  <div class="h-0 flex-1 flex flex-col overflow-y-auto text-gray-700">
    <nav>
      <h4 v-if="!collapsed" class="border-b border-gray-200 px-5 py-2 mb-2">
        Reports
      </h4>
      <inertia-link
        v-for="{ label, route, icon } in links"
        :key="route.toString()"
        :class="{ active: routeActive(route.name) }"
        :href="route.toString() + queryString"
        class="inline-flex justify-between"
        ``
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
    links() {
      return [
        { label: 'Regions', route: this.route('regions'), icon: GLOBE },
        { label: 'Timeline', route: this.route('timeline'), icon: CALENDAR },
        {
          label: 'Suppression',
          route: this.route('suppression'),
          icon: LOCK_CLOSED
        }
      ];
    },
    queryString() {
      return !!this.$page.selectedRegions.length
        ? '?regions=' + btoa(JSON.stringify(this.$page.selectedRegions))
        : '';
    }
  }
};
</script>

<style scoped>
a {
  @apply flex w-full items-center px-4 py-2 mb-2 text-base text-gray-700 leading-6 font-medium rounded-md transition ease-in-out duration-150;
}
.active {
  @apply text-blue-700 bg-gray-100;
}
.active:focus {
  @apply outline-none;
}
a:hover {
  @apply bg-gray-100 text-gray-700;
}
</style>
