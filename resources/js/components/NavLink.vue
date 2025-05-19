<template>
  <inertia-link
    :href="href"
    class="block px-3 py-2 rounded hover:bg-gray-200"
    :class="{ 'bg-gray-300 font-semibold': isActive }"
  >
    {{ label }}
  </inertia-link>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, Link as InertiaLink } from '@inertiajs/vue3';
const props = defineProps({
  href: String,
  label: String,
});

const page = usePage();

const isActive = computed(() => {
  return page.url === props.href;
});
</script>

<style scoped>
</style>
