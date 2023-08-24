<template>
    <div>
        <div class="modal fade" :id="'updateTaskModal-' + taskId" tabindex="-1" role="dialog" aria-labelledby="updateTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateTaskModalLabel">Update Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input v-model="updatedTask.title" />
                        <textarea v-model="updatedTask.description"></textarea>
                        <select v-model="updatedTask.status">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="updateTask">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        taskId: Number
    },
    data() {
        return {
            updatedTask: {
                title: '',
                description: '',
                status: ''
            }
        };
    },
    methods: {
        updateTask() {
            axios.put(`/api/tasks/${this.taskId}`, this.updatedTask)
                .then(response => {
                    this.$emit('update-success'); // Emit event to parent component
                    this.$root.$emit('hide-bs-modal', 'updateTaskModal-' + this.taskId); // Emit event to hide the modal
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }
};
</script>
