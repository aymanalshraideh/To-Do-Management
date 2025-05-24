<template>
    <div v-if="modelValue" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow">
            <h2 class="text-lg font-bold mb-4">Send Notification</h2>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Notification Type</label>
                <div class="flex items-center gap-4">
                    <label class="flex items-center">
                        <input type="radio" value="private" v-model="form.type" class="mr-2" />
                        Private
                    </label>
                    <label class="flex items-center">
                        <input type="radio" value="public" v-model="form.type" class="mr-2" />
                        Public
                    </label>
                </div>
            </div>

            <form @submit.prevent="send">
                <div class="mb-4" v-if="form.type=='private'">
                    <label class="block mb-1">Select Users</label>
                    <select multiple v-model="form.user_ids" class="w-full border p-2 rounded">
                        <option v-for="user in users" :key="user.id" :value="user.id">
                            {{ user.name }} ({{ user.email }})
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <input v-model="form.title" type="text" placeholder="Notification Title"
                        class="w-full border p-2 rounded" />
                    <div v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</div>
                </div>

                <div class="mb-3">
                    <textarea v-model="form.message" placeholder="Notification Message"
                        class="w-full border p-2 rounded"></textarea>
                    <div v-if="form.errors.message" class="text-red-500 text-sm">{{ form.errors.message }}</div>
                </div>

                <div class="flex justify-end gap-2">
                    <button @click="$emit('update:modelValue', false)" type="button"
                        class="px-3 py-1 border rounded">Cancel</button>
                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded" :disabled="form.processing">
                        {{ form.processing ? 'Sending...' : 'Send' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch, onMounted } from 'vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css';

const props = defineProps({
    modelValue: Boolean,
    users: Array,
    selectedIds: Array
})

const emit = defineEmits(['update:modelValue'])

const form = useForm({
    user_ids: [],
    title: '',
    message: '',
    type: 'private'
})

function send() {
    form.post('/notifications/send', {
        onSuccess: () => {
            form.reset()
            emit('update:modelValue', false)
            toast.success('Notification sent successfully')
        }
    })
}
watch(() => props.modelValue, (show) => {
    if (show) {
        form.reset()
        form.user_ids = props.selectedIds ?? []
    }
})

onMounted(() => {
    form.user_ids = props.selectedIds ?? []
    form.type = form.user_ids.length > 0 ? 'private' : 'public'
})

</script>
