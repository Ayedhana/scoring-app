import { createRouter, createWebHistory } from "vue-router"
import DashboardView from "@/views/DashboardView.vue"
import LoginView from "@/views/LoginView.vue"
import RegisterView from "@/views/RegisterView.vue"
import ClientDetailsView from "@/views/ClientDetailsView.vue"
import HistoriqueView from '../views/HistoriqueView.vue'
import NotFoundView from "@/views/NotFoundView.vue"
import RapportsView from "@/views/RapportsView.vue"
import ParametresView from "@/views/ParametresView.vue"
import UsersView from "@/views/UsersView.vue"
import UserCreateView from "@/views/UserCreateView.vue"
import UserEditView from "@/views/UserEditView.vue"
import { useAuth } from "@/composables/useAuth"

const routes = [
  { path: "/", component: DashboardView, meta: { requiresAuth: true } },
  { path: "/login", component: LoginView, meta: { requiresAuth: false } },
  { path: "/register", component: RegisterView, meta: { requiresAuth: false } },
  { path: "/clients/:id", name: "clientDetails", component: ClientDetailsView, meta: { requiresAuth: true } },
  { path: '/historique', component: HistoriqueView, meta: { requiresAuth: true } },
  { path: '/rapports', component: RapportsView, meta: { requiresAuth: true } },
  { path: '/parametres', component: ParametresView, meta: { requiresAuth: true, requiresAdmin: true } },
  { path: '/users', component: UsersView, meta: { requiresAuth: true, requiresAdmin: true } },
  { path: '/users/create', component: UserCreateView, meta: { requiresAuth: true, requiresAdmin: true } },
  { path: '/users/:id/edit', component: UserEditView, meta: { requiresAuth: true, requiresAdmin: true } },
  { path: "/:pathMatch(.*)*", name: "NotFound", component: NotFoundView },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const { isAuthenticated, user } = useAuth()
  const requiresAuth = to.meta.requiresAuth !== false
  const requiresAdmin = to.meta.requiresAdmin === true

  if (requiresAuth && !isAuthenticated.value) {
    return next('/login')
  }

  if (to.path === '/login' && isAuthenticated.value) {
    return next('/')
  }

  if (requiresAdmin && user.value?.role !== 'admin') {
    return next('/')
  }

  next()
})

export default router