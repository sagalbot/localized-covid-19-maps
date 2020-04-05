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
        <HamburgerButton @sidebar:open="sidebarOpen = true" />
      </div>
      <main class="flex-1 relative z-0 overflow-y-auto pt-2 pb-6 md:py-6">
        <slot></slot>
      </main>
    </div>
  </div>
</template>

<script>
import DesktopSidebar from '../components/Sidebar/DesktopSidebar';
import MobileSidebar from '../components/Sidebar/MobileSidebar';
import HamburgerButton from '../components/Buttons/HamburgerButton';

export default {
  name: 'SidebarLayout',
  components: { HamburgerButton, MobileSidebar, DesktopSidebar },
  data: () => ({
    sidebarOpen: false
  })
};
</script>
