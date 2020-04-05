<template>
  <div class="md:hidden">
    <SidebarOverlay @click="$emit('sidebar:close')" />
    <div class="fixed inset-0 flex z-40">
      <div class="flex-1 flex flex-col max-w-xs w-full bg-white">
        <CloseSidebarButton v-on="$listeners" />
        <PrimaryNavigation :collapsed="false" v-on="$listeners" />
        <FooterNavigation v-on="$listeners" />
      </div>
      <div class="flex-shrink-0 w-14"></div>
    </div>
  </div>
</template>
<script>
import SidebarOverlay from './SidebarOverlay';
import CloseSidebarButton from '../Buttons/CloseSidebarButton';
import PrimaryNavigation from '../Navigation/PrimaryNavigation';
import FooterNavigation from '../Navigation/FooterNavigation';

export default {
  name: 'MobileSidebar',
  components: {
    SidebarOverlay,
    CloseSidebarButton,
    FooterNavigation,
    PrimaryNavigation
  },
  props: {
    sidebarOpen: {
      required: true
    }
  }
};
</script>
