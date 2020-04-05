<template>
  <div class="absolute top-0 right-0 -mr-14 p-1">
    <button
      @click="$emit('sidebar:close')"
      class="flex items-center justify-center h-12 w-12 rounded-full focus:outline-none focus:bg-gray-600"
    >
      <CloseIcon />
    </button>
  </div>
</template>
<script>
import CloseIcon from "../Icons/CloseIcon";

export default {
  name: "CloseSidebarButton",
  components: { CloseIcon }
};
</script>
