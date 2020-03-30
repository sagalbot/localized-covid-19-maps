<template>
  <div class="flex-1 flex flex-col max-h-screen">
    <header class="flex items-center p-3 border-b border-gray-200">
      <button @click="sidebar = !sidebar">
        <Icon
          name="cheveron-left"
          class="mr-2 transform transition-transform duration-200"
          :class="{ 'rotate-180': !sidebar }"
        />
      </button>
      <h1 class="text-teal-400 text-xl font-bold leading-none">
        COVID-19 Tracker
      </h1>
    </header>

    <main class="flex flex-1 overflow-y-hidden">
      <transition name="slide-left">
        <RegionSelect v-if="sidebar" />
      </transition>
    </main>
  </div>
</template>

<script>
import Icon from '../Components/Icon';
import RegionSelect from '../Components/RegionSelect';
export default {
  name: 'App',
  components: { Icon, RegionSelect },
  data: () => ({
    sidebar: true
  }),
  computed: {}
};
</script>

<style>
.v-select {
  min-width: 300px;
}
</style>
