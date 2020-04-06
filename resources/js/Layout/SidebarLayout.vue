<template>
  <div class="h-screen flex overflow-hidden bg-white">
    <MobileSidebar
      v-if="sidebarOpen"
      sidebar-open="sidebarOpen"
      @sidebar:close="sidebarOpen = false"
      key="mobileSidebar"
    />
    <DesktopSidebar key="desktopSidebar" />

    <div class="flex flex-col w-0 flex-1 overflow-hidden">
      <div class="md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3">
        <HamburgerButton @click="sidebarOpen = true" />
      </div>
      <main class="flex-1 relative z-0 overflow-y-auto">
        <slot></slot>
      </main>
    </div>
  </div>
</template>

<script>
import DesktopSidebar from '../Components/Sidebar/DesktopSidebar';
import MobileSidebar from '../Components/Sidebar/MobileSidebar';
import HamburgerButton from '../Components/Buttons/HamburgerButton';
import PrimaryNavigation from '../Components/Navigation/PrimaryNavigation';

export default {
  name: 'SidebarLayout',
  components: {
    HamburgerButton,
    MobileSidebar,
    DesktopSidebar,
    PrimaryNavigation
  },
  data: () => ({
    sidebarOpen: false
  })
};
</script>
