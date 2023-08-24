<template>
   <div>
       <form @submit.prevent="addTask">
           <div class="mb-3">
               <label for="exampleInputEmail1" class="form-label">Title</label>
               <input type="test" v-model="title" class="form-control" >
               <div v-if="titleError" class="text-danger">Title is required.</div>


           </div>
           <div class="mb-3">
               <label for="exampleInputPassword1" class="form-label">Description</label>
              <textarea class="form-control" v-model="description"></textarea>
           </div>

           <div class="mb-3">
               <label for="user_id" class="form-label">Assign User</label>
               <input v-model="searchTerm" @input="fetchUsers" class="form-control" placeholder="Search users">
               <select v-model="selectedUserId" class="form-select" id="user_id">
                   <option value="">Select a user</option>
                   <option v-for="user in users.data" :key="user.id" :value="user.id">{{ user.name }}</option>
               </select>
               <div v-if="userError" class="text-danger">User is required.</div>
           </div>

           <button type="submit" class="btn btn-primary">Submit</button>
       </form>
   </div>
</template>
<script>
import axios from 'axios';

export default {
    data() {
        return {
            title: '',
            description: '',
            titleError: false,
            userError: false,
            selectedUserId: null,
            users: {
                data: []
            },
            searchTerm: ''

        };
    },
    beforeMount() {
        this.fetchUsers()
    },
    methods: {
        addTask() {
            if (this.title.trim() === '') {
                this.titleError = true;
                this.$toast.error('Title is required.');
                return;
            }
            if (!this.selectedUserId) {
                this.userError = true;
                this.$toast.error('Please select a user.');
                return;
            }

            this.titleError = false;
            this.userError = false;
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

                axios.post('/api/task/store', {
                    _token: csrfToken,
                    title: this.title,
                    description: this.description,
                    user_id: this.selectedUserId
                })
                    .then(response => {
                        // Handle successful response
                        if(response.data.status == true) {
                            this.$toast.success(response.data.message);
                            this.title = "";
                            this.description = "";
                        }
                    })
                    .catch(error => {
                        // Handle error
                        this.$toast.error('An error occurred');
                    });



        },
        fetchUsers(page = 1) {
            axios.get('/api/task/users', {
                params: {
                    per_page: 10,
                    search: this.searchTerm,
                    page: page
                }
            })
                .then(response => {
                    this.users = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

    }
}
</script>
