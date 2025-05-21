<template>
  <AppLayout>
    <div class="p-4">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Locations</h1>
        <button @click="openAddModal"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Add Location
        </button>
      </div>

      <input
        type="text"
        v-model="search"
        @input="searchLocations"
        placeholder="Search locations..."
        class="border px-3 py-2 rounded w-full mb-4"
      />

      <table class="w-full bg-white border">
        <thead>
          <tr>
            <th class="border px-4 py-2">#</th>
            <th class="border px-4 py-2">Location Name</th>
            <th class="border px-4 py-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(location, index) in locations.data" :key="location.id">
            <td class="border px-4 py-2">{{ index + 1 }}</td>
            <td class="border px-4 py-2">{{ location.name }}</td>
            <td class="border px-4 py-2 flex gap-2">
              <button @click="editLocation(location)" class="text-blue-600 hover:underline">Edit</button>
              <button @click="deleteLocation(location.id)" class="text-red-600 hover:underline">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-4">
        <button
          v-if="locations.prev_page_url"
          @click="goToPage(locations.prev_page_url)"
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 mr-2"
        >Prev</button>
        <button
          v-if="locations.next_page_url"
          @click="goToPage(locations.next_page_url)"
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
        >Next</button>
      </div>

      <LocationModal
        :show="showModal"
        :location="locationToEdit"
        :onClose="closeModal"
      />
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import LocationModal from './LocationModal.vue'

const props = defineProps({
  locations: Object,
  filters: Object,
})

const search = ref(props.filters.search || '')
const showModal = ref(false)
const locationToEdit = ref(null)

const openAddModal = () => {
  locationToEdit.value = null
  showModal.value = true
}

const editLocation = (location) => {
  locationToEdit.value = location
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const searchLocations = () => {
  router.get(route('locations.index'), { search: search.value }, { preserveState: true })
}

const goToPage = (url) => {
  router.visit(url, { preserveState: true })
}

const deleteLocation = (id) => {
  if (confirm('Are you sure?')) {
    router.delete(route('locations.destroy', id), {
      preserveScroll: true,
    })
  }
}
</script>
