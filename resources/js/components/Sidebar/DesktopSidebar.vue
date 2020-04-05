<template>
  <div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 border-r border-gray-200 bg-white">
      <PrimaryNavigation />
      <FooterNavigation />
    </div>
  </div>
</template>

<script>
import PrimaryNavigation from '../Navigation/PrimaryNavigation';
import FooterNavigation from '../Navigation/FooterNavigation';

export default {
  name: 'DesktopSidebar',
  components: { FooterNavigation, PrimaryNavigation }
};
</script>
