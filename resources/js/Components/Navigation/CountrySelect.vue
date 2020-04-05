<template>
  <ul
    class="overflow-y-scroll max-h-full pr-3 mr-3 countries relative text-gray-700"
  >
    <li class="mb-2 sticky top-0 bg-white">
      <input
        type="search"
        v-model="countryFilter"
        placeholder="filter this list..."
        class="border-2 border-gray-300 w-full px-1 py-1"
      />
      <div class="flex justify-between mt-2">
        <button
          class="py-1 px-2 border-gray-200 border rounded"
          @click="selected = []"
        >
          clear selected
        </button>
      </div>
    </li>
    <li
      v-for="{ country, cases } in casesPerCountry"
      :key="country"
      class="py-1 my-1 border-b border-gray-200"
      :class="{ 'text-red-600': selected.includes(country) }"
    >
      <label class="flex items-center justify-between ">
        <span>
          <input
            type="checkbox"
            v-model="selected"
            :value="country"
            class="mr-1"
          />
          {{ country }}
        </span>
        <span>{{ parseFloat(cases).toLocaleString("en") }}</span>
      </label>
    </li>
  </ul>
</template>
<script>
export default {
  name: "CountrySelect",
  props: {
    casesPerCountry: {},
    countryFilter: {},
    selected: { type: Function }
  }
};
</script>
