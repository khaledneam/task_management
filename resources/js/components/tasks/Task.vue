<template>
    <div>
        <div v-if="!showModal">
            <div class="mb-3">
                <input type="text" v-model="searchTasks" @input="handleSearch" placeholder="Search...">
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>User</th>
                    <th>Description</th>
                    <th>status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="task in tasks.data" :key="task.id">
                    <td>{{ task.title }}</td>
                    <td v-if="task.user">{{ task.user.name }}</td>
                    <td v-else>No user assigned</td>
                    <td>{{ task.description }}</td>
                    <td>{{ task.status }}</td>
                    <td>
                        <button class="btn btn-danger" @click="deleteTask(task.id)">Delete Task</button>
                        <button class="btn btn-primary" @click="showUpdateModal(task)">Update</button>

                    </td>
                </tr>
                <tr v-if="tasks.data.length === 0">
                    <td colspan="6" class="text-center">No matching tasks found.</td>
                </tr>
                </tbody>
            </table>

            <!-- Display pagination controls -->
            <pagination :data="tasks" @pagination-change-page="fetchTasks"></pagination>
        </div>
        <div v-else>
            <form @submit.prevent="updateTask">
                <input type="hidden" v-model="updatedTask.id">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Title</label>
                    <input type="test" v-model="updatedTask.title" class="form-control" >
                    <div v-if="titleError" class="text-danger">Title is required.</div>


                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Description</label>
                    <textarea class="form-control" v-model="updatedTask.description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="updatedTask" class="form-label">Status</label>
                    <select v-model="updatedTask.status" id="updatedTask">
                        <option value="waiting">waiting</option>
                        <option value="pending">Pending</option>
                        <option value="complete">Completed</option>
                    </select>
                </div>



                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-primary" @click="closeModal">close</button>
            </form>
        </div>

    </div>
</template>

<script>
import axios from 'axios';
import UpdateTaskModal from '../tasks/UpdateTask.vue';

export default {
    components: {
        UpdateTaskModal
    },
    data() {
        return {
            tasks: {
                data: []
            },
            searchTasks: '',
            showModal: false,
            title: '',
            description: '',
            status: '',
            titleError: false,
            userError: false,
            selectedUserId: null,
            users: {
                data: []
            },
            searchTerm: '',
            updatedTask: {
                title: '',
                description: '',
                status: '',
                id: '',
            }
        };
    },
    created() {
        this.fetchTasks();
    },

    methods: {
        fetchTasks(page = 1) {
            axios.get('/api/tasks', {
                params: {
                    page: page,
                    search: this.searchTasks
                }
            })
                .then(response => {
                    this.tasks = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        deleteTask(taskId) {
            axios.delete(`/api/task/destroy/${taskId}`)
                .then(response => {
                    this.fetchTasks();
                    this.showStatusUpdateToast(response.data.status, response.data.message);
                })
                .catch(error => {
                    console.error(error);
                });
        },
        showStatusUpdateToast(status,msg) {

            if (status) {
                this.$toast.success(msg);
            } else {
                this.$toast.error('An error occurred while updating status');
            }
        },
        handleSearch() {
            // Fetch tasks when search term changes
            this.fetchTasks();
        },
        showUpdateModal(task) {
            this.updatedTask.title = task.title;
            this.updatedTask.description = task.description;
            this.updatedTask.status = task.status;
            this.updatedTask.id = task.id;
            this.showModal = true;
        },
        closeModal(){
            this.showModal = false;
        },
        updateTask() {
            axios.put(`/api/task/update/${this.updatedTask.id}`, this.updatedTask)
                .then(response => {
                   if(response.data.status == true){
                       this.showModal = false;
                       this.fetchTasks();
                       this.showStatusUpdateToast(response.data.status, response.data.message);
                   }

                })
                .catch(error => {
                    console.error(error);
                });
        }


    }
}
</script>
