<template>
  <div class="fixed inset-0 z-50 bg-black/30 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Edit Role</h2>
        <button @click="$emit('close')" class="text-red-500 font-bold text-lg">×</button>
      </div>

      <form @submit.prevent="form.put(route('admin.roles.update', role.id), {
        preserveScroll: true,
        onSuccess: () => {
          form.reset()
          emit('close')
        }
      })">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Role Name</label>
          <input
            v-model="form.name"
            type="text"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
            placeholder="Enter new role name"
          />
          <div v-if="form.errors.name" class="text-sm text-red-600 mt-1">
            {{ form.errors.name }}
          </div>
        </div>

        <div class="flex justify-end">
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            Update Role
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({ role: Object })
const emit = defineEmits(['close'])

const form = useForm({
  name: props.role.name
})
</script>
