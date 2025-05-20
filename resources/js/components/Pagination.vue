<template>
  <div v-if="links.length > 3" class="flex justify-center mt-6 space-x-1">
    <template v-for="(link, key) in links" :key="key">
      <!-- Disabled Element -->
      <span
        v-if="!link.url"
        class="px-3 py-2 text-sm text-gray-400 cursor-default"
        v-html="link.label"
      />

      <!-- Active Link -->
      <Link
        v-else
        :href="link.url"
        class="px-3 py-2 text-sm rounded border"
        :class="{
          'bg-blue-600 text-white border-blue-600': link.active,
          'bg-white text-gray-700 hover:bg-gray-100 border-gray-300': !link.active
        }"
        v-html="link.label"
      />
    </template>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  links: {
    type: Array,
    required: true
  }
});
</script>
