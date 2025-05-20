<template>
    <AppLayout>

        <div>
            <div class="flex justify-between items-center mb-4">
                <input v-model="filters.search" @input="updateSearch" type="text" placeholder="Search role..."
                    class="border p-2 rounded w-1/3" />

                <button @click="showAddModal = true"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Add Role
                </button>


            </div>

            <table class="w-full bg-white shadow rounded">
                <thead>
                    <tr class="text-left border-b">
                        <th class="p-3">#</th>
                        <th class="p-3">Role Name</th>
                        <th class="p-3">Permissions</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(role, index) in roles.data" :key="role.id" class="border-b">
                        <td class="p-3">{{ index + 1 }}</td>
                        <td class="p-3">{{ role.name }}</td>
                        <td class="p-3">
                            <button @click="openAssignModal(role)"
                                class="bg-emerald-600 text-white text-sm px-3 py-1 rounded hover:bg-emerald-700 transition">
                                Assign
                            </button>
                        </td>
                        <!-- <td class="p-3">
                            <button @click="deleteRole(role.id)"
                                class="text-sm text-red-500 hover:underline">Delete</button>
                        </td> -->
                        <td class="px-4 py-2 space-x-2">
                            <button @click="openEditModal(role)" class="text-blue-600 hover:underline text-sm">
                                Edit
                            </button>
                            <button class='text-red-600'@click="deleteRole(role.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Pagination :links="roles.links" />
            <AssignPermissionModal v-if="showAssignModal" :role="selectedRole" @close="showAssignModal = false" />
            <AddRoleModal v-if="showAddModal" @close="showAddModal = false" />
            <EditRoleModal v-if="showEditModal" :role="selectedRole" @close="showEditModal = false" />
      
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'
import AssignPermissionModal from './AssignPermissionModal.vue'
import AddRoleModal from './AddRoleModal.vue'
import EditRoleModal from './EditRoleModal.vue'
import AppLayout from '@/Layouts/AppLayout.vue';

const showAddModal = ref(false)
const showEditModal = ref(false)
const selectedRole = ref(null)

const props = defineProps({
  roles: Object,
  filters: Object
})


function openEditModal(role) {
  selectedRole.value = role
  showEditModal.value = true
}

const showAssignModal = ref(false)

const filters = ref({ ...props.filters })

const updateSearch = () => {
  router.get(route('admin.roles.index'), { search: filters.value.search }, { preserveState: true })
}

const openAssignModal = (role) => {
  selectedRole.value = role
  showAssignModal.value = true
}

const deleteRole = (id) => {
  if (confirm("Are you sure you want to delete this role?")) {
    router.delete(route('admin.roles.destroy', id))
  }
}
</script>
