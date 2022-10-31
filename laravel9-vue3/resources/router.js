import { createRouter, createWebHistory} from "vue-router";
import TaskListComponent from "./js/components/TaskListComponent.vue";
import TaskShowComponent from "./js/components/TaskShowComponent.vue";

const routes = [
    {
        path: '/tasks',
        name: 'task.list',
        component: TaskListComponent
    },
    {
        path: '/tasks/:taskId',
        name: 'task.show',
        component: TaskShowComponent,
        props: true,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
