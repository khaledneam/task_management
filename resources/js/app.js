require('./bootstrap');
import { createApp } from 'vue'
import HelloWorld from './components/Welcome.vue';
import TaskList from './components/tasks/Task.vue';
import TaskAdd from './components/tasks/Add.vue';
import pagination from './components/Pagination.vue';
import UpdateTaskModal  from './components/tasks/UpdateTask.vue';


import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
const app = createApp({});
app.use(VueToast);

app.component('hello-world', HelloWorld)
app.component('task-list', TaskList)
app.component('pagination', pagination)
app.component('task-add', TaskAdd)
app.component('update-task-modal', UpdateTaskModal)

app.mount('#app')
