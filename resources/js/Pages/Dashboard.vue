<template>
  <div class="flex-1 flex flex-col">
    <header class="p-3 border-b border-gray-200">
      <Icon name="menu" />
      <h1 class="text-teal-400 text-xl font-bold leading-none">
        COVID-19 Tracker
      </h1>
    </header>

    <main class="flex flex-1 overflow-y-hidden"></main>
  </div>
</template>

<script>
import Icon from '../Components/Icon';
export default {
  name: 'App',
  components: { Icon },
  data: () => ({}),
  computed: {
    countries() {
      return this.$page.countries.data;
    },
    provinces() {
      return this.$page.provinces.data;
    }
  }
};
</script>

<style>
.v-select {
  min-width: 300px;
}
</style>
