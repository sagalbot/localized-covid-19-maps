<template>
  <div class="h-0 flex-1 flex flex-col pb-4 overflow-y-auto">
    <div
      class="flex-shrink-0 flex items-center px-4 py-3 border-b border-gray-200"
    >
      <h1>COVID-19 Dashboard</h1>
    </div>
    <nav class="mt-3 px-2">
      <inertia-link href="/" @click="$emit('sidebar:close')">
        Timeline
      </inertia-link>
      <inertia-link href="/suppression" @click="$emit('sidebar:close')">
        Suppression
      </inertia-link>
    </nav>
  </div>
</template>

<style scoped>
a {
  @apply flex w-full items-center px-2 py-2 text-base leading-6 font-medium rounded-md transition ease-in-out duration-150;
}
.active {
  @apply my-1 text-gray-900 bg-gray-100;
}
a:hover {
  @apply bg-gray-300 text-gray-700;
}
.active:focus {
  @apply outline-none text-gray-900 bg-gray-100;
}
</style>
