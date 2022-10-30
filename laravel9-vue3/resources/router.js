import { createRouter, createWebHistory} from "vue-router";
import TaskListComponent from "./js/components/TaskListComponent.vue";

const routes = [
    {
        path: '/tasks',
        name: 'task.list',
        component: TaskListComponent
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
