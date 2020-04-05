<template>
  <div class="hidden md:flex md:flex-shrink-0">
    <div
      class="flex flex-col border-r border-gray-200 bg-white"
      :class="{ 'w-64': !collapsed }"
    >
      <HamburgerButton @click="collapsed = !collapsed" />
      <PrimaryNavigation :collapsed="collapsed" />
      <FooterNavigation v-if="!collapsed" />
    </div>
  </div>
</template>

<script>
import PrimaryNavigation from '../Navigation/PrimaryNavigation';
import FooterNavigation from '../Navigation/FooterNavigation';
import HamburgerButton from '../Buttons/HamburgerButton';

export default {
  name: 'DesktopSidebar',
  components: { HamburgerButton, FooterNavigation, PrimaryNavigation },
  data: () => ({
    collapsed: false
  })
};
</script>
