<script setup>
defineProps({ products: Array });
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Product List</h1>
    <div class="space-y-4">
      <div
        v-for="product in products"
        :key="product.id"
        class="rounded-xl border p-4 shadow-sm hover:shadow-md transition"
      >
        <h2 class="text-lg font-semibold">{{ product.title }}</h2>
        <p class="text-gray-600">{{ product.description }}</p>
        <p class="text-blue-600 font-bold">${{ product.price }}</p>
        <p class="text-sm text-gray-400">Stock: {{ product.stock }}</p>
      </div>
    </div>
  </div>
</template>
