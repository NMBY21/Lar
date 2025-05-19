<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  description: '',
  price: '',
  stock: '',
  image: null,
})

function submit() {
  form.post('/admin/products', {
    forceFormData: true,

    })
}
</script>

<template>
    <div class="p-6 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Create Product</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Title</label>
                <input v-model="form.title" type="text" class="w-full rounded-lg border p-2" />
                <div v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium">Description</label>
                <textarea v-model="form.description" class="w-full rounded-lg border p-2"></textarea>
                <div v-if="form.errors.description" class="text-red-500 text-sm">{{ form.errors.description }}</div>
            </div>

            <div>
                <label class="block text-sm font-medium">Price</label>
                <input v-model="form.price" type="number" step="0.01" class="w-full rounded-lg border p-2" />
                <div v-if="form.errors.price" class="text-red-500 text-sm">{{ form.errors.price }}</div>
            </div>

            <div>
                <label class="block text-sm font-medium">Stock</label>
                <input v-model="form.stock" type="number" class="w-full rounded-lg border p-2" />
                <div v-if="form.errors.stock" class="text-red-500 text-sm">{{ form.errors.stock }}</div>
            </div>

            <div>
                <label class="block text-sm font-medium">Product Image</label>
                <input type="file" @change="e => form.image = e.target.files[0]" class="w-full border p-2 rounded-lg" />
                <div v-if="form.errors.image" class="text-red-500 text-sm">{{ form.errors.image }}</div>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                :disabled="form.processing">
                Save Product
            </button>
        </form>
    </div>
</template>
