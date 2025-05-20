<template>
  <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative">
      <h2 class="text-xl font-bold mb-4">{{ user ? 'Edit' : 'Add' }} System User</h2>

      <form @submit.prevent="submitForm">
        <div class="mb-4">
          <label class="block text-sm mb-1">Name</label>
          <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" />
          <span v-if="form.errors.name" class="text-red-500 text-xs">{{ form.errors.name }}</span>
        </div>

        <div class="mb-4">
          <label class="block text-sm mb-1">Email</label>
          <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" />
          <span v-if="form.errors.email" class="text-red-500 text-xs">{{ form.errors.email }}</span>
        </div>

        <div class="mb-4">
          <label class="block text-sm mb-1">Phone</label>
          <input v-model="form.phone" type="text" class="w-full border rounded px-3 py-2" />
          <span v-if="form.errors.phone" class="text-red-500 text-xs">{{ form.errors.phone }}</span>
        </div>

        <div class="mb-4" v-if="!user">
          <label class="block text-sm mb-1">Password</label>
          <input v-model="form.password" type="password" class="w-full border rounded px-3 py-2" />
          <span v-if="form.errors.password" class="text-red-500 text-xs">{{ form.errors.password }}</span>
        </div>

        <div class="mb-4" v-if="!user">
          <label class="block text-sm mb-1">Confirm Password</label>
          <input v-model="form.password_confirmation" type="password" class="w-full border rounded px-3 py-2" />
        </div>

        <div class="mb-4">
          <label class="block text-sm mb-1">Role</label>
          <select v-model="form.role_id" class="w-full border rounded px-3 py-2">
            <option value="">Select Role</option>
            <option v-for="role in roles" :key="role.id" :value="role.id">
              {{ role.name }}
            </option>
          </select>
          <span v-if="form.errors.role_id" class="text-red-500 text-xs">{{ form.errors.role_id }}</span>
        </div>

        <div class="flex justify-end gap-4">
          <button type="button" @click="$emit('close')" class="bg-gray-200 text-gray-800 px-4 py-2 rounded">
            Cancel
          </button>
          <button type="submit" class="bg-black text-white px-4 py-2 rounded">
            {{ user ? 'Update' : 'Create' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

const props = defineProps({
  user: Object,
  roles: Array,
})

const emit = defineEmits(['close', 'saved'])

const form = useForm({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
  role_id: '',
})

watch(
  () => props.user,
  (newUser) => {
    if (newUser) {
      form.name = newUser.name
      form.email = newUser.email
      form.phone = newUser.phone
      form.role_id = newUser.roles?.[0]?.id || ''
    }
  },
  { immediate: true }
)

const submitForm = () => {
  if (props.user) {
    form.put(route('admin.system-users.update', props.user.id), {
      onSuccess: () => {
        emit('saved')
      },
    })
  } else {
    form.post(route('admin.system-users.store'), {
      onSuccess: () => {
        emit('saved')
      },
    })
  }
}
</script>
