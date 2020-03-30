<template>
  <ul
    class="overflow-y-scroll max-h-full pr-3 mr-3 countries relative text-gray-700 border-r border-gray-200"
  >
    <li class="mb-2 sticky top-0 bg-white">
      <input
        type="search"
        v-model="query"
        placeholder="filter this list..."
        class="border-1 border-gray-300 w-full px-1 py-1"
      />
      <div class="flex justify-between mt-2">
        <button
          @click="show = 'countries'"
          :disabled="show === 'countries'"
          class="py-1 px-2 border-gray-200 border rounded"
        >
          Country/Region
        </button>
        <button
          @click="show = 'provinces'"
          :disabled="show === 'provinces'"
          class="py-1 px-2 border-gray-200 border rounded"
        >
          Province/State
        </button>
      </div>
    </li>
    <li
      v-for="region in filtered"
      :key="region.id"
      class="py-1 px-2 my-1 border-b border-gray-200"
      :class="{ 'text-red-600': selected.includes(region) }"
    >
      <label class="flex items-center justify-between ">
        <span>
          <input
            type="checkbox"
            v-model="selected"
            :value="region"
            class="mr-1"
          />
          {{ region.name }}
        </span>
        <span>{{ parseFloat(region.cases).toLocaleString('en') }}</span>
      </label>
    </li>
  </ul>
</template>

<script>
export default {
  name: 'RegionSelect',
  data: () => ({
    query: '',
    selected: [],
    show: 'countries'
  }),
  computed: {
    filtered() {
      return this[this.show];
      // return this[this.show].filter(region =>
      //   region.toLowerCase().includes(this.query.toLowerCase())
      // );
    },
    countries() {
      return this.$page.countries.data;
    },
    provinces() {
      return this.$page.provinces.data;
    }
  }
};
</script>

<style scoped></style>
