<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
        <div class="bg-white rounded-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-6">
            <div class="sticky top-0 bg-white z-10 pb-4">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-xl font-semibold">Assign Permission to Role: {{ role.name }}</h2>
                    <button @click="$emit('close')" class="text-red-500 font-bold">X</button>
                </div>

                <form @submit.prevent="assignPermissions">
                    <table class="w-full border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2 border">#</th>
                                <th class="p-2 border">Permission</th>
                                <th class="p-2 border text-center">Show</th>
                                <th class="p-2 border text-center">Add</th>
                                <th class="p-2 border text-center">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(perm, index) in groupedPermissions" :key="perm.base">
                                <td class="p-2 border">{{ index + 1 }}</td>
                                <td class="p-2 border capitalize">{{ perm.base }}</td>
                                <td class="p-2 border text-center">
                                    <input type="checkbox" :checked="form.permissions[perm.base]?.includes('show')"
                                        @change="togglePermission(perm.base, 'show')" />
                                </td>
                                <td class="p-2 border text-center">
                                    <input type="checkbox" :checked="form.permissions[perm.base]?.includes('add')"
                                        @change="togglePermission(perm.base, 'add')" />
                                </td>
                                <td class="p-2 border text-center">
                                    <input type="checkbox" :checked="form.permissions[perm.base]?.includes('delete')"
                                        @change="togglePermission(perm.base, 'delete')" />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Assign Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'
const emit = defineEmits(['close'])

const props = defineProps({
  role: Object
})

const form = useForm({
  permissions: {}
})

const groupedPermissions = ref([])

onMounted(async () => {
const { data } = await axios.get('/admin/permissions') 
//   const { data } = await axios.get('/permissions')

  // Group permissions by base name
  const grouped = {}

  data.forEach(perm => {
    const [action, base] = perm.name.split(' ')
    if (!grouped[base]) grouped[base] = []
    grouped[base].push(action)
  })

  groupedPermissions.value = Object.keys(grouped).map(base => ({
    base,
    actions: grouped[base]
  }))

  // Initialize form values with current role permissions
  props.role.permissions.forEach(perm => {
    const [action, base] = perm.name.split(' ')
    if (!form.permissions[base]) form.permissions[base] = []
    form.permissions[base].push(action)
  })
})
const togglePermission = (base, action) => {
    if (!form.permissions[base]) {
        form.permissions[base] = []
    }

    const index = form.permissions[base].indexOf(action)
    if (index === -1) {
        form.permissions[base].push(action)
    } else {
        form.permissions[base].splice(index, 1)
    }
}

const assignPermissions = () => {
  form.post(route('admin.roles.assign-permissions', props.role.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      emit('close')
    }
  })
}
</script>
