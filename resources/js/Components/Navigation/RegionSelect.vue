<template>
  <nav class="flex flex-col">
    <ul class="overflow-y-scroll h-full relative text-gray-700">
      <li class="sticky top-0 bg-white">
        <header
          class="flex items-center justify-between border-b border-t border-gray-200 px-5 py-2"
        >
          Selected Regions
          <button class="hover:text-blue-500" @click="showAll = !showAll">
            toggle all
          </button>
        </header>

        <input
          type="search"
          v-if="showAll"
          v-model="query"
          placeholder="filter this list..."
          class="border-b border-gray-200 w-full px-5 py-2"
        />
      </li>
      <li
        v-for="region in regions"
        v-if="showAll || isSelected(region)"
        :key="region.id"
        class="py-1 my-1 border-b border-gray-200"
        :class="{ 'text-red-600': isSelected(region) }"
      >
        <label class="flex items-center items-start px-5">
          <input type="checkbox" :checked="isSelected(region)" class="mr-1" />
          {{ region.name }}
        </label>
      </li>
    </ul>
  </nav>
</template>

<script>
import { sortBy } from 'lodash-es';

export default {
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
    //
    isSelected({ type, id }) {
      return (
        this.$page.selectedRegions.filter(selected => selected.id === id)
          .length !== 0
      );
    }
  }
};
</script>

<style scoped></style>
