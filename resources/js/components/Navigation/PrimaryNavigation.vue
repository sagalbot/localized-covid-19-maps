<template>
  <div class="h-0 flex-1 flex flex-col overflow-y-auto text-gray-700">
    <nav>
      <h4 class="border-b border-gray-200 px-5 py-2 mb-2">
        Reports
      </h4>
      <inertia-link
        v-for="{ label, route } in links"
        :key="route.toString()"
        :class="{ active: routeActive(route.name) }"
        :href="route.toString()"
      >
        {{ route.name }}
      </inertia-link>
    </nav>
    <RegionSelect class="flex-grow" />
  </div>
</template>

<script>
import RegionSelect from './RegionSelect';
import state from '../../state';

export default {
  components: { RegionSelect },
  computed: {
    showLinks() {
      return !state.showRegions;
    },
    links() {
      return [
        { label: 'Timeline', route: this.route('timeline') },
        { label: 'Suppression', route: this.route('suppression') }
      ];
    }
  },
  methods: {
    // @click.prevent="onClick(route)"
    async onClick(route) {
      this.$emit('sidebar:close');
      await this.$inertia.visit(route.toString());
      this.$forceUpdate();
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
