<script setup>
import { Link, router } from '@inertiajs/vue3'

defineProps({
  products: Array
})

function deleteProduct(id) {
  if (confirm("Are you sure you want to delete this product?")) {
    router.delete(`/admin/products/${id}`);
  }
}
</script>

<template>
  <div class="p-6 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Product List</h1>
      <Link
        href="/admin/products/create"
        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
      >
        + Add Product
      </Link>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <div
        v-for="product in products"
        :key="product.id"
        class="bg-white rounded-2xl shadow p-4"
      >
        <img
          v-if="product.image_path"
          :src="`/storage/${product.image_path}`"
          alt="Product Image"
          class="w-full h-48 object-cover rounded-lg mb-3"
        />
        <h2 class="text-xl font-semibold">{{ product.title }}</h2>
        <p class="text-gray-600 text-sm mb-1 truncate">{{ product.description }}</p>
        <p class="text-blue-600 font-bold text-lg mb-2">${{ product.price }}</p>
        <p class="text-sm text-gray-500">Stock: {{ product.stock }}</p>

        <div class="mt-4 flex justify-between">
          <Link
            :href="`/admin/products/${product.id}/edit`"
            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm"
          >
            Edit
          </Link>
          <button
            @click="deleteProduct(product.id)"
            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
