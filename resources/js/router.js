import { createRouter, createWebHistory } from 'vue-router'
import Homepage from './pages/Homepage.vue'
import ConfigureCustomList from './pages/ConfigureCustomList.vue'
import AddStudent from './pages/AddStudent.vue'

const routes = [
  { path: '/homepage', component: Homepage },
  { path: '/configure-custom-list', component: ConfigureCustomList },
  { path: '/add-student', component: AddStudent },
  { path: '/:pathMatch(.*)*', redirect: '/homepage' }, // fallback
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router