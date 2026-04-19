import { createRouter, createWebHistory } from "vue-router"
import DashboardView from "@/views/DashboardView.vue"
import LoginView from "@/views/LoginView.vue"
import RegisterView from "@/views/RegisterView.vue"
import ClientDetailsView from "@/views/ClientDetailsView.vue"
import HistoriqueView from '../views/HistoriqueView.vue'
import NotFoundView from "@/views/NotFoundView.vue"
import { useAuth } from "@/composables/useAuth"
import RapportsView from "@/views/RapportsView.vue"
import ParametresView from "@/views/ParametresView.vue"









const routes = [
  {
    path: "/",
    component: DashboardView,
    meta: { requiresAuth: true }
  },
  {
    path: "/login",
    component: LoginView,
    meta: { requiresAuth: false }
  },
  {
    path: "/register",
    component: RegisterView,
    meta: { requiresAuth: false }
  },
  {
    path: "/clients/:id",
    name: "clientDetails",
    component: ClientDetailsView,
    meta: { requiresAuth: true }
  },
  {
    path: '/historique',
    component: HistoriqueView,
    meta: { requiresAuth: true }
  },
  {
  path: '/rapports',
  component: RapportsView,
  meta: { requiresAuth: true }
},
{
  path: '/parametres',
  component: ParametresView,
  meta: { requiresAuth: true }
},
  { 
    path: "/:pathMatch(.*)*", 
    name: "NotFound", 
    component: NotFoundView 
  },
]



const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Navigation guard for authentication
router.beforeEach((to, from, next) => {
  const { isAuthenticated } = useAuth()
  const requiresAuth = to.meta.requiresAuth !== false

  if (requiresAuth && !isAuthenticated.value) {
    next('/login')
  } else if (to.path === '/login' && isAuthenticated.value) {
    next('/')
  } else {
    next()
  }
})

export default router 