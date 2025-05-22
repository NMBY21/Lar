<template>
  <AppLayout>
    <div class="p-4">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Employees</h1>
        <button
          @click="openAddModal"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Add Employee
        </button>
      </div>

      <!-- Search -->
      <div class="mb-4">
        <input
          type="text"
          v-model="search"
          @input="searchEmployees"
          placeholder="Search employees..."
          class="border px-3 py-2 rounded w-full"
        />
      </div>

      <!-- Table -->
      <table class="min-w-full bg-white border">
        <thead>
          <tr>
            <th class="border px-4 py-2">#</th>
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Type</th>
            <th class="border px-4 py-2">Phone</th>
            <th class="border px-4 py-2">File</th>
            <th class="border px-4 py-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(employee, index) in employees.data" :key="employee.id">
            <td class="border px-4 py-2">{{ index + 1 }}</td>
            <td class="border px-4 py-2">{{ employee.name }}</td>
            <td class="border px-4 py-2 capitalize">{{ employee.employee_type }}</td>
            <td class="border px-4 py-2">{{ employee.phone }}</td>
            <td class="border px-4 py-2">
              <a
                v-if="employee.file_path"
                :href="`/storage/${employee.file_path}`"
                target="_blank"
                class="text-blue-600 underline"
              >
                View
              </a>
              <span v-else>-</span>
            </td>
            <td class="border px-4 py-2 space-x-2">
              <button @click="openEditModal(employee)" class="text-yellow-600 hover:underline">Edit</button>
              <button @click="deleteEmployee(employee.id)" class="text-red-600 hover:underline">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-4">
        <button
          v-if="employees.prev_page_url"
          @click="goToPage(employees.prev_page_url)"
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 mr-2"
        >
          Prev
        </button>
        <button
          v-if="employees.next_page_url"
          @click="goToPage(employees.next_page_url)"
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
        >
          Next
        </button>
      </div>

      <EmployeeModal
        :show="showModal"
        :onClose="closeModal"
        :employee="employeeToEdit"
      />
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import EmployeeModal from './EmployeeModal.vue'

const props = defineProps({
  employees: Object,
  filters: Object
})

const search = ref(props.filters.search || '')
const showModal = ref(false)
const employeeToEdit = ref(null)

const searchEmployees = () => {
  router.get(route('employees.index'), { search: search.value }, { preserveState: true })
}

const goToPage = (url) => {
  router.visit(url, { preserveState: true })
}

const deleteEmployee = (id) => {
  if (confirm('Are you sure you want to delete this employee?')) {
    router.delete(route('employees.destroy', id), {
      preserveScroll: true
    })
  }
}

const openAddModal = () => {
  employeeToEdit.value = null
  showModal.value = true
}

const openEditModal = (employee) => {
  employeeToEdit.value = employee
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}
</script>
