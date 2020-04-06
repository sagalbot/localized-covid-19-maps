<template>
  <ul class="overflow-y-scroll h-full w-full relative text-gray-700">
    <li class="sticky top-0 bg-white">
      <header
        class="flex items-center justify-between border-b border-gray-200 px-5 py-2"
      >
        Selected Regions
      </header>

      <input
        type="search"
        v-model="query"
        placeholder="filter this list..."
        class="border-b border-gray-200 w-full px-5 py-2"
      />
    </li>
    <li
      v-for="region in regions"
      :key="region.type + region.id"
      class="py-1 my-1 border-b border-gray-200"
      :class="{ 'text-red-600': isSelected(region) }"
    >
      <label class="flex items-center items-start px-5">
        <input
          type="checkbox"
          :checked="isSelected(region)"
          @input="updateSelected(region, $event)"
          class="mr-1"
        />
        {{ region.name }}
      </label>
    </li>
  </ul>
</template>

<script>
import { sortBy } from 'lodash-es';
import SidebarLayout from '../Layout/SidebarLayout';

export default {
  layout: SidebarLayout,
  name: 'RegionSelect',
  data: () => ({
    query: '',
    showAll: false
  }),
  computed: {
    regions() {
      const regions = [
        ...this.$page.countries,
        ...this.$page.provinces
      ].filter(({ name }) =>
        this.query.length
          ? name.toLowerCase().includes(this.query.toLowerCase())
          : true
      );

      return sortBy(regions, 'name');
    },
    selected() {
      return this.$page.selectedRegions;
    }
  },
  methods: {
    updateSelected(region, { target }) {
      let updated = this.selected;

      if (target.checked) {
        updated.push(region);
      } else {
        updated = updated.filter(
          selected => JSON.stringify(region) !== JSON.stringify(selected)
        );
      }

      let { origin, pathname } = window.location;
      const url = `${origin}${pathname}?regions=${btoa(
        JSON.stringify(updated)
      )}`;

      this.$inertia.visit(url);
    },
    isSelected(region) {
      return this.$page.selectedRegions.find(
        ({ type, id }) => region.type === type && region.id === id
      );
    }
  }
};
</script>
