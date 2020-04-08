<template>
  <table class="table-auto overflow-y-scroll w-full relative text-gray-700">
    <thead class="sticky top-0 bg-white">
      <tr class="border-b border-gray-200">
        <th colspan="4" class="border-b border-gray-200 py-2 px-3">
          <div class="flex w-full justify-between">
            <input
              type="search"
              v-model="query"
              placeholder="filter this list..."
              class="border border-gray-300 rounded px-2 py-1 flex-1 mr-3"
            />
            <label
              class="inline-flex items-center border border-gray-300 rounded px-2 py-1 mr-3 font-normal hover:border-blue-500 hover:text-blue-500 transition transition-colors duration-200"
              :class="{ 'border-blue-500 text-blue-500': hideUnselected }"
            >
              <input type="checkbox" v-model="hideUnselected" class="mr-2" />
              hide unselected
            </label>
            <button
              @click="selected = []"
              :disabled="selected.length === 0"
              class="inline-flex items-center border border-gray-300 rounded px-2 py-1 hover:border-yellow-700 hover:text-yellow-700 transition transition-colors duration-200"
            >
              clear selected
              <Icon name="close-outline" :size="4" class="ml-2" />
            </button>
          </div>
        </th>
      </tr>
      <tr>
        <th class="border-b border-gray-300 text-gray-500 text-left px-4 py-2">
          Region
        </th>
        <th class="border-b border-gray-300 text-gray-500 text-right px-4 py-2">
          Confirmed
        </th>
        <th class="border-b border-gray-300 text-gray-500 text-right px-4 py-2">
          Recovered
        </th>
        <th class="border-b border-gray-300 text-gray-500 text-right px-4 py-2">
          Deaths
        </th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="region in filtered"
        :key="region.type + region.id"
        class="px-2 my-1 border-b border-gray-200"
        :class="{ 'text-blue-500': isSelected(region) }"
      >
        <td class="text-left px-4">
          <label class="flex flex-1 py-2 items-center items-start">
            <input
              type="checkbox"
              :checked="isSelected(region)"
              @input="updateSelected(region, $event)"
              class="mr-3"
            />
            {{ region.name }}
            <span
              v-if="region.country_name !== region.name"
              class="text-gray-500"
            >
              , {{ region.country_name }}
            </span>
          </label>
        </td>
        <td class="text-yellow-600 text-right px-4">
          {{ format(region.latest.confirmed) }}
        </td>
        <td class="text-green-600 text-right px-4">
          {{ format(region.latest.recovered) }}
        </td>
        <td class="text-gray-500 text-right px-4">
          {{ format(region.latest.deaths) }}
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
import SidebarLayout from '../Layout/SidebarLayout';
import Icon from '../Components/Icons/Icon';
import format from 'sugar/number/format';

export default {
  layout: SidebarLayout,
  name: 'Regions',
  components: { Icon },
  props: {
    regions: {
      required: true,
      type: Array
    }
  },
  data() {
    return {
      query: '',
      hideUnselected: false,
      selected: this.$page.selectedRegions
    };
  },
  watch: {
    selected(selected) {
      let { origin, pathname } = window.location;
      let regions = btoa(JSON.stringify(selected));

      this.$inertia.visit(origin + pathname + '?regions=' + regions, {
        preserveState: true,
        preserveScroll: true
      });
    }
  },
  computed: {
    filtered() {
      return this.regions.filter(({ name, country_name, id, type }) => {
        const query = this.query.toLowerCase();
        const includesQuery =
          name.toLowerCase().includes(query) ||
          country_name.toLowerCase().includes(query);

        if (this.hideUnselected) {
          return this.isSelected({ id, type }) && includesQuery;
        }

        return includesQuery;
      });
    }
  },
  methods: {
    format,
    updateSelected({ type, id }, { target }) {
      if (target.checked) {
        return this.selected.push({ type, id });
      }

      this.selected = this.selected.filter(
        selected => selected.type !== type && selected.id !== id
      );
    },
    isSelected(region) {
      return this.selected.find(
        ({ type, id }) => region.type === type && region.id === id
      );
    }
  }
};
</script>
