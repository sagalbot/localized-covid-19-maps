<template>
  <div class="flex-1 flex flex-col">
    <header class="p-3 border-b border-gray-200">
      <h1 class="text-red-500 text-xl font-bold leading-none">
        COVID-19 Tracker
      </h1>
    </header>

    <main class="flex flex-1 overflow-y-hidden"></main>
  </div>
</template>

<script>
export default {
  name: 'App',
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
