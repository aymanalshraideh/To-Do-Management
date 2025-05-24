<script setup>
import { onMounted, ref } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TaskModal from '@/Components/Tasks/TaskModal.vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css';

const page = usePage()
const roles = page.props.auth.roles[0]
const permissions = page.props.auth.permissions
const props = defineProps({
    tasks: Object,
    users: Array,
})

const showModal = ref(false)
const selectedTask = ref(null)
const isAdmin = ref(false)
const selectedTasks = ref([])

function toggleSelectAll(event) {
    if (event.target.checked) {
        selectedTasks.value = props.tasks.data.map(task => task.id)
    } else {
        selectedTasks.value = []
    }
}

function lockSelectedTasks(locked) {
    axios.post(route('admin.tasks.bulkLock'), {
        is_locked: locked,
        task_ids: selectedTasks.value
    })
        .then((res) => {
            // alert(res.data.message || 'Selected tasks locked successfully.')
            toast.success(res.data.message || 'Selected tasks locked successfully.')

        })
        .catch(error => {
            console.error(error)
            toast.error('Failed to lock tasks.')
            // alert('Failed to lock tasks.')
        })
}


function openAddTaskModal() {
    selectedTask.value = null
    showModal.value = true
}

function openEditTaskModal(task) {
    selectedTask.value = JSON.parse(JSON.stringify(task))
    showModal.value = true
}

function closeModal() {
    showModal.value = false
    selectedTask.value = null
}

function refresh() {
    router.reload()
    closeModal()
    if (selectedTask.value) {
        toast.success('Task updated successfully.')
    } else {
        toast.success('Task created successfully.')
    }
}
function hasPermission(permission) {
    return permissions.includes(permission)
}
function updateTaskStatus(taskId, newStatus) {
    axios.put(route('admin.tasks.updateStatus', taskId), { status: newStatus })
        .then((response) => {
            // alert(response.data.message || 'Task status updated successfully.')
            toast.success(response.data.message || 'Task status updated successfully.')
        })
        .catch((error) => {
            console.error(error)
            
            // alert('Something went wrong!')
        })
}


onMounted(() => {
    if (roles == 'admin') {
        isAdmin.value = true
    }

})
</script>

<template>

    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
                <button v-if="hasPermission('create task')" @click="openAddTaskModal"
                    class="px-4 py-2 bg-blue-500 rounded text-white">
                    + Add Task
                </button>

            </div>
        </template>
        <div class="mb-4 flex" >
            <button v-if="hasPermission('lock task')" @click="lockSelectedTasks(true)" class="bg-red-600 text-white px-4 py-1 rounded"
                :disabled="selectedTasks.length === 0">
                Lock Selected Tasks
            </button>
            <button v-if="hasPermission('lock task')" @click="lockSelectedTasks(false)" class="bg-green-600 text-white px-4 py-1 rounded"
                :disabled="selectedTasks.length === 0">
                UnLock Selected Tasks
            </button>
        </div>

        <div class="flex justify-center items-center mt-6 p-5 bg-white rounded shadow-md">

            <table class="table-auto w-full mt-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left" >
                            <input type="checkbox" @change="toggleSelectAll($event)" />Lock
                        </th>

                        <th class="px-4 py-2 text-left">Title</th>
                        <th class="px-4 py-2 text-left">description</th>
                         <th class="px-4 py-2 text-left">Priority</th>
                        <th class="px-4 py-2 text-left">Status</th>                
                        <th class="px-4 py-2 text-center">Actions</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="task in props.tasks.data" :key="task.id" class="border-t">
                        <td class="px-4 py-2" >
                            <input type="checkbox" :value="task.id" v-model="selectedTasks" />
                        </td>


                        <td class="px-4 py-2">{{ task.title }}</td>
                        <td class="px-4 py-2">{{ task.description }}</td>
                  

                        <td class="px-4 py-2 capitalize">{{ task.priority }}</td>
                              <td class="px-4 py-2 capitalize">
                            <template v-if="hasPermission('edit task status')">
                                <select class="border rounded p-1 text-sm" :value="task.status"
                                    @change="updateTaskStatus(task.id, $event.target.value)">
                                    <option value="todo">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="qa">QA</option>
                                    <option value="done">Done</option>
                                </select>
                            </template>

                            <template v-else>
                                {{ task.status }}
                            </template>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button v-if="hasPermission('edit task')" @click="openEditTaskModal(task)"
                                class="px-3 py-1 bg-green-500 text-white rounded mr-2">Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>

    <TaskModal v-model:show="showModal" :task="selectedTask" :lockTask="hasPermission('lock task')" :users="props.users"
        @saved="refresh" />
</template>
