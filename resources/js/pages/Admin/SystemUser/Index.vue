<template>
    <AppLayout>
  <div>
    <div class="flex justify-between items-center mb-4">
      <input
        v-model="search"
        @input="filterUsers"
        type="text"
        placeholder="Search system users..."
        class="border rounded px-4 py-2 w-full max-w-sm"
      />
      <button @click="showCreateModal = true" class="ml-4 bg-blue-600 text-white px-4 py-2 rounded-md  hover:bg-blue-700">Add System User</button>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
      <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2">#</th>
            <th class="px-4 py-2">Username</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Phone</th>
            <th class="px-4 py-2">Role</th>
            <th class="px-4 py-2">Registered In</th>
            <th class="px-4 py-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, index) in users.data" :key="user.id" class="border-b">
            <td class="px-4 py-2">{{ index + 1 + (users.current_page - 1) * users.per_page }}</td>
            <td class="px-4 py-2">{{ user.name }}</td>
            <td class="px-4 py-2">{{ user.email }}</td>
            <td class="px-4 py-2">{{ user.phone }}</td>
            <td class="px-4 py-2">
              <select
                v-model="user.role_id"
                @change="assignRole(user)"
                class="border rounded px-2 py-1"
              >
                <option v-for="role in roles" :value="role.id" :key="role.id">{{ role.name }}</option>
              </select>
            </td>
            <td class="px-4 py-2">{{ formatDate(user.created_at) }}</td>
            <td class="px-4 py-2">
              <button class="text-blue-600 mr-2" @click="editUser(user)">Edit</button>
              <button class="text-red-600" @click="deleteUser(user.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Pagination :links="users.links" class="mt-6" />

    <SystemUserFormModal
      v-if="showCreateModal"
      :roles="roles"
      :user="editingUser"
      @close="closeModal"
      @saved="refresh"
    />
  </div>
  </AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'
import SystemUserFormModal from './FormModal.vue'
import AppLayout from '@/Layouts/AppLayout.vue';


const props = defineProps({
  users: Object,
  roles: Array,
  filters: Object,
})

const search = ref(props.filters.search || '')
const showCreateModal = ref(false)
const editingUser = ref(null)

const filterUsers = () => {
  router.get(route('admin.system-users.index'), { search: search.value }, { preserveState: true })
}

const assignRole = (user) => {
  router.put(route('admin.system-users.update', user.id), {
    name: user.name,
    email: user.email,
    phone: user.phone,
    role_id: user.role_id,
  })
}

const editUser = (user) => {
  editingUser.value = { ...user, role_id: user.roles[0]?.id }
  showCreateModal.value = true
}

const deleteUser = (id) => {
  if (confirm('Are you sure you want to delete this user?')) {
    router.delete(route('admin.system-users.destroy', id))
  }
}

const closeModal = () => {
  editingUser.value = null
  showCreateModal.value = false
}

const refresh = () => {
  closeModal()
  router.reload()
}

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString()
}
</script>
