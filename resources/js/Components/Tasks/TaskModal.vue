<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'
import { onMounted ,ref} from 'vue'
import { usePage } from '@inertiajs/vue3'
const page = usePage()
const props = defineProps({
    task: Object,
    users: Array,
    show: Boolean,
    lockTask:Boolean
})
const roles = page.props.auth.roles[0]
const emit = defineEmits(['update:show', 'saved'])
const isAdmin = ref(false)
const form = useForm({
    title: '',
    description: '',
    priority: 1,
    status: 'pending',
    due_date: '',
    due_time: '',
    is_locked: false,
    user_id: ''
})

// Reset form on task change
watch(() => props.task, (task) => {
    if (task!=null) {
        form.defaults({ ...task })
        form.reset()
           form.is_locked = !!task.is_locked
    } else {
        form.reset({
            title: '',
            description: '',
            priority: 1,
            status: 'pending',
            due_date: '',
            due_time: '',
            is_locked: false,
            user_id: ''
        })
    }
}, { immediate: true })

function submit() {
    if (props.task?.id) {
        form.put(route('admin.tasks.update', props.task.id), {
            onSuccess: () => emitSaved()
        })
    } else {
        form.post(route('admin.tasks.store'), {
            onSuccess: () => {
                emitSaved()
                console.log('te');

            },
            onError: (error) => {
                console.log(error)
            }
        })
    }
}

function emitSaved() {
    form.reset()
    form.clearErrors()
    emit('saved')
    emit('update:show', false)
}
onMounted(() => {
    if (roles == 'admin') {
        isAdmin.value = true
    }
    console.log(isAdmin.value);
    
})
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
        <div class="bg-white p-6 rounded-lg w-full max-w-lg max-h-[90vh] overflow-y-auto relative shadow-lg">
            <button @click="emit('update:show', false)" class="absolute top-2 right-2 text-red-600 text-xl">✕</button>
            <h2 class="text-xl font-bold mb-4">{{ props.task ? 'Edit Task' : 'Add Task' }}</h2>

            <form @submit.prevent="submit">
                <div class="space-y-3">
                    <div>
                        <label class="block">Title</label>
                        <input v-model="form.title" type="text" class="w-full border p-2 rounded" :readonly="form.is_locked == 1" />
                        <div v-if="form.errors.title" class="text-red-600 text-sm">{{ form.errors.title }}</div>
                    </div>

                    <div>
                        <label class="block">Description</label>
                        <textarea v-model="form.description" class="w-full border p-2 rounded" :readonly="form.is_locked == 1"></textarea>
                    </div>

                    <div>
                        <label class="block">Priority</label>
                        <select v-model="form.priority" class="w-full border p-2 rounded" :disabled="form.is_locked == 1">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>

                        </select>
                    </div>


                    <div>
                        <label class="block">Due Date</label>
                        <input v-model="form.due_date" type="date" class="w-full border p-2 rounded" :readonly="form.is_locked == 1"/>
                    </div>

                    <div>
                        <label class="block">Due Time</label>
                      <input v-model="form.due_time" type="time" step="1" class="w-full border p-2 rounded" :readonly="form.is_locked == 1" />

                    </div>
                    <div>
                        <label class="block mb-1 font-semibold">Assigned User</label>
                        <select v-model="form.user_id" class="w-full border p-2 rounded" :disabled="form.is_locked == 1">
                            <option disabled value="">-- Select User --</option>
                            <option v-for="u in props.users" :key="u.id" :value="u.id">{{ u.name }}</option>
                        </select>
                        <div v-if="form.errors.user_id" class="text-red-600 text-sm">{{ form.errors.user_id }}</div>
                    </div>

                    <div>
                        <label class="block">Status</label>
                        <select v-model="form.status" class="w-full border p-2 rounded" :disabled="form.is_locked == 1">
                            <option value="todo">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="qa">QA</option>
                            <option value="done">Done</option>

                        </select>
                    </div>

                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" v-model="form.is_locked" class="mr-2" v-if="isAdmin&&props.lockTask" />
                            Lock Task (Admin only)
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded"
                            :disabled="form.processing">
                            {{ form.processing ? 'Saving...' : (props.task ? 'Update' : 'Create') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
