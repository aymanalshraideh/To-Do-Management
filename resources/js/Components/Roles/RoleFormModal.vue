<template>

   <div v-if="modelValue" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
      <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
        <h2 class="text-lg font-bold mb-4">{{ role?.id ? 'Edit Role' : 'Add Role' }}</h2>

        <form @submit.prevent="submit">
          <div class="mb-3">
            <input
              v-model="form.name"
              placeholder="Role Name"
              class="w-full border px-3 py-2 rounded"
            />
            <div v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</div>
          </div>

          <div class="mb-4 max-h-40 overflow-y-auto border rounded p-2">
            <label
              v-for="permission in permissions"
              :key="permission.id"
              class="flex items-center space-x-2 mb-1"
            >
              <input
                type="checkbox"
                :value="permission.name"
                v-model="form.permissions"
              />
              <span>{{ permission.name }}</span>
            </label>
            <div v-if="form.errors.permissions" class="text-red-600 text-sm">{{ form.errors.permissions }}</div>
          </div>

          <div class="flex justify-end gap-2">
            <button
              type="button"
              @click="$emit('update:modelValue', false)"
              class="px-3 py-1 border rounded"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-3 py-1 bg-green-600 text-white rounded"
            >
              {{ role?.id ? 'Update' : 'Add' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  
</template>

<script setup>
import { watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  modelValue: Boolean,
  role: Object,
  permissions: Array,
})

const emit = defineEmits(['update:modelValue'])

const form = useForm({
  name: '',
  permissions: [],
})

watch(
  () => props.role,
  (newRole) => {
    form.name = newRole?.name || ''
    form.permissions = newRole?.permissions?.map(p => p.name) || []
    form.clearErrors()
  },
  { immediate: true }
)

function submit() {
  if (props.role?.id) {
    form.patch(`/roles/${props.role.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        form.reset()
        emit('update:modelValue', false)
      },
    })
  } else {
    form.post('/roles', {
      preserveScroll: true,
      onSuccess: () => {
        form.reset()
        emit('update:modelValue', false)
      },
    })
  }
}
</script>
