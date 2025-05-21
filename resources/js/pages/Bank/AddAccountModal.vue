<template>
  <div v-if="show" class="fixed inset-0 z-50 bg- bg-opacity-30 flex items-center justify-center">
        <div class="bg-white rounded shadow p-6 w-full max-w-md">
      <h2 class="text-lg font-semibold mb-4">Add Account</h2>
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block mb-1 text-sm font-medium">Account</label>
          <input
            v-model="form.account"
            type="text"
            class="w-full border px-3 py-2 rounded"
            :class="{ 'border-red-500': form.errors.account }"
          />
          <p v-if="form.errors.account" class="text-red-500 text-sm mt-1">
            {{ form.errors.account }}
          </p>
        </div>

        <div class="flex justify-end gap-2">
          <button type="button" @click="onClose" class="px-4 py-2 border rounded">
            Cancel
          </button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Save
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
  show: Boolean,
  onClose: Function,
  bankId: Number,
  accountToEdit: Object, // if null => add, if object => edit
})

const form = useForm({
  bank_id: props.bankId,
  account: '',
})

watch(() => props.accountToEdit, (val) => {
  if (val) {
    form.account = val.account
  } else {
    form.reset()
  }
})

const submit = () => {
  if (props.accountToEdit) {
    // Edit mode
    form.put(route('admin.accounts.update', props.accountToEdit.id), {
      onSuccess: () => {
        form.reset()
        props.onClose()
      },
    })
  } else {
    // Add mode
    form.post(route('admin.accounts.store'), {
      onSuccess: () => {
        form.reset()
        props.onClose()
      },
    })
  }
}
</script>

