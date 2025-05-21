<template>
    
        <div v-if="modelValue" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
                <h2 class="text-lg font-bold mb-4">
                    {{ user?.id ? 'Edit User' : 'Add User' }}
                </h2>

                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <input v-model="form.name" placeholder="Name" class="w-full border px-3 py-2 rounded" />
                        <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
                    </div>
                    <div class="mb-3">
                        <input v-model="form.email" placeholder="Email" class="w-full border px-3 py-2 rounded" />
                        <div v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</div>
                    </div>
                    <div class="mb-4" v-if="!user?.id">
                        <input v-model="form.password" type="password" placeholder="Password" class="w-full border px-3 py-2 rounded" />
                        <div v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="$emit('update:modelValue', false)" class="px-3 py-1 border rounded">Cancel</button>
                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded">
                            {{ user?.id ? 'Update' : 'Add' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

</template>

<script setup>
import { watch, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    modelValue: Boolean,
    user: Object
})

const emit = defineEmits(['update:modelValue'])

const form = useForm({
    name: '',
    email: '',
    password: ''
})

watch(() => props.user, (newUser) => {
    form.name = newUser?.name || ''
    form.email = newUser?.email || ''
    form.password = ''
})

function submit() {
    if (props.user?.id) {
        form.patch(`/users/${props.user.id}`, {
            onSuccess: () => {
                form.reset()
                emit('update:modelValue', false)
            }
        })
    } else {
        form.post('/users', {
            onSuccess: () => {
                form.reset()
                emit('update:modelValue', false)
            }
        })
    }
}
</script>
