<template>
  <footer
    class="flex-shrink-0 flex border-t border-gray-200 p-3 text-sm text-gray-600"
  >
    <ul>
      <li class="mb-3">
        <a
          href="https://github.com/sagalbot/covid"
          class="flex-shrink-0 block focus:outline-none inline-flex text-blue-400"
        >
          GitHub
          <Icon name="link" :size="4" class="text-gray-400 ml-2" />
        </a>
      </li>

      <li>Updated {{ lastUpdate }}</li>
    </ul>
  </footer>
</template>

<script>
import Icon from '../Icons/Icon';
export default {
  components: { Icon },
  computed: {
    lastUpdate() {
      return new Date(this.$page.lastUpdate).toLocaleString();
    }
  }
};
</script>
