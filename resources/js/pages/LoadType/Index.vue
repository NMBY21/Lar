<template>
  <AppLayout>
    <div class="p-4">
      <div class="flex justify-between mb-4">
        <h1 class="text-xl font-bold">Load Types</h1>
        <button
          @click="openModal()"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Add Load Type
        </button>
      </div>

      <input
        v-model="search"
        @input="searchLoadTypes"
        placeholder="Search load types..."
        class="border px-3 py-2 rounded w-full mb-4"
      />

      <table class="min-w-full bg-white border">
        <thead>
          <tr>
            <th class="border px-4 py-2">#</th>
            <th class="border px-4 py-2">Load Type Name</th>
            <th class="border px-4 py-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(loadType, index) in loadTypes.data" :key="loadType.id">
            <td class="border px-4 py-2">{{ index + 1 }}</td>
            <td class="border px-4 py-2">{{ loadType.name }}</td>
            <td class="border px-4 py-2 space-x-2">
              <button @click="openModal(loadType)" class="text-blue-600 hover:underline">Edit</button>
              <button @click="deleteLoadType(loadType.id)" class="text-red-600 hover:underline">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-4">
        <button
          v-if="loadTypes.prev_page_url"
          @click="goToPage(loadTypes.prev_page_url)"
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 mr-2"
        >
          Prev
        </button>
        <button
          v-if="loadTypes.next_page_url"
          @click="goToPage(loadTypes.next_page_url)"
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
        >
          Next
        </button>
      </div>

      <LoadTypeModal
        :show="showModal"
        :onClose="closeModal"
        :loadType="loadTypeToEdit"
      />
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import LoadTypeModal from './LoadTypeModal.vue'

const props = defineProps({
  loadTypes: Object,
  filters: Object
})

const search = ref(props.filters.search || '')
const showModal = ref(false)
const loadTypeToEdit = ref(null)

const searchLoadTypes = () => {
  router.get(route('load-types.index'), { search: search.value }, { preserveState: true })
}

const openModal = (loadType = null) => {
  loadTypeToEdit.value = loadType
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  loadTypeToEdit.value = null
}

const deleteLoadType = (id) => {
  if (confirm('Are you sure?')) {
    router.delete(route('load-types.destroy', id), {
      preserveScroll: true,
    })
  }
}

const goToPage = (url) => {
  router.visit(url, { preserveState: true })
}
</script>
