<template>
  <div class="app-shell">

    <!-- SIDEBAR -->
    <aside class="sidebar" :class="{ collapsed: sidebarCollapsed }">

      <!-- Logo / Brand -->
      <div class="sidebar-brand">
        <div class="brand-logo">
          <img src="@/assets/btl_banque_tuniso_libyenne_logo.jpg" alt="BTL" class="brand-img" />
        </div>
        <div class="brand-text">
          <span class="brand-name">BTL</span>
          <span class="brand-sub">Scoring Client</span>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="sidebar-nav">
        <div class="nav-section-label">MENU</div>

        <router-link to="/" class="nav-item" active-class="nav-item--active">
          <span class="nav-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
              <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
          </span>
          <span class="nav-label">Dashboard</span>
        </router-link>

        <div class="nav-section-label" style="margin-top: 12px;">ANALYSES</div>

        <router-link to="/historique" class="nav-item" active-class="nav-item--active">
          <span class="nav-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
          </span>
          <span class="nav-label">Historique Scores</span>
        </router-link>

        <router-link to="/rapports" class="nav-item" active-class="nav-item--active">
          <span class="nav-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
          </span>
          <span class="nav-label">Rapports</span>
        </router-link>

        <!-- ✅ Section ADMIN — visible seulement pour admin -->
        <template v-if="isAdmin">
          <div class="nav-section-label" style="margin-top: 12px;">ADMIN</div>

          <router-link to="/users" class="nav-item" active-class="nav-item--active">
            <span class="nav-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
            </span>
            <span class="nav-label">Utilisateurs</span>
          </router-link>

          <router-link to="/parametres" class="nav-item" active-class="nav-item--active">
            <span class="nav-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.07 4.93l-1.41 1.41M4.93 4.93l1.41 1.41M19.07 19.07l-1.41-1.41M4.93 19.07l1.41-1.41M12 2v2M12 20v2M2 12h2M20 12h2"/>
              </svg>
            </span>
            <span class="nav-label">Paramètres</span>
          </router-link>
        </template>

      </nav>

      <!-- User info en bas -->
      <div class="sidebar-footer">
        <div class="sidebar-user">
          <div class="user-avatar">{{ userInitials }}</div>
          <div class="user-info">
            <span class="user-name">{{ userName }}</span>
            <span class="user-role">{{ userRoleLabel }}</span>
          </div>
        </div>
        <button class="logout-btn" @click="logout" title="Se déconnecter">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
        </button>
      </div>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <div class="main-wrapper">

      <!-- Topbar -->
      <header class="topbar">
        <button class="collapse-btn" @click="sidebarCollapsed = !sidebarCollapsed">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>

        <div class="breadcrumb">
          <span class="breadcrumb-root">BTL</span>
          <span class="breadcrumb-sep">/</span>
          <span class="breadcrumb-current">{{ pageTitle }}</span>
        </div>

        <div class="topbar-right">
          <!-- Badge rôle -->
          <span :class="['role-badge', isAdmin ? 'role-admin' : 'role-analyst']">
            {{ userRoleLabel }}
          </span>
          <div class="online-badge">
            <span class="online-dot"></span>
            En ligne
          </div>
          <span class="topbar-date">{{ currentDate }}</span>
        </div>
      </header>

      <!-- Zone de contenu -->
      <main class="page-content">
        <slot />
      </main>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const sidebarCollapsed = ref(false)

// ✅ Récupérer l'utilisateur connecté
const user = JSON.parse(localStorage.getItem('user') || '{}')
const userName = computed(() => user.name || 'Utilisateur')
const userInitials = computed(() =>
  userName.value.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
)

// ✅ Vérifier le rôle
const isAdmin = computed(() => user.role === 'admin')
const userRoleLabel = computed(() => {
  if (user.role === 'admin') return 'Administrateur'
  if (user.role === 'analyst') return 'Analyste Crédit'
  return 'Auditeur'
})

const pageTitles = {
  '/':             'Gestion des Clients',
  '/historique':   'Historique des Scores',
  '/rapports':     'Rapports',
  '/parametres':   'Paramètres',
  '/users':        'Gestion des Utilisateurs',
}

const pageTitle = computed(() => {
  const path = route.path
  for (const [key, val] of Object.entries(pageTitles)) {
    if (path === key || path.startsWith(key + '/')) return val
  }
  return 'BTL Scoring'
})

const currentDate = computed(() =>
  new Date().toLocaleDateString('fr-FR', {
    day: 'numeric', month: 'long', year: 'numeric'
  })
)

function logout() {
  localStorage.removeItem('access_token')
  localStorage.removeItem('user')
  router.push('/login')
}
</script>

<style scoped>
:root {
  --sidebar-w: 240px;
  --sidebar-w-collapsed: 64px;
  --topbar-h: 56px;
}

.app-shell {
  display: flex;
  min-height: 100vh;
  background: #f0f3f8;
  font-family: 'Segoe UI', system-ui, sans-serif;
}

/* ─── Sidebar ─────────────────────────────────────────────── */
.sidebar {
  width: 240px;
  background: #0f4f4f;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  height: 100vh;
  position: sticky;
  top: 0;
  overflow: hidden;
  transition: width 0.25s ease;
  z-index: 10;
}

.sidebar.collapsed { width: 64px; }

.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 20px 16px 18px;
  border-bottom: 1px solid rgba(255,255,255,0.07);
  flex-shrink: 0;
}

.brand-logo {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  background: white;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  overflow: hidden;
}

.brand-img { width: 32px; height: 32px; object-fit: contain; }

.brand-text { display: flex; flex-direction: column; overflow: hidden; }
.brand-name { font-size: 15px; font-weight: 700; color: #fff; white-space: nowrap; }
.brand-sub  { font-size: 10px; color: #99cece; white-space: nowrap; }

.sidebar-nav {
  flex: 1;
  padding: 12px 8px;
  overflow-y: auto;
  overflow-x: hidden;
}
.sidebar-nav::-webkit-scrollbar { width: 0; }

.nav-section-label {
  font-size: 9px;
  font-weight: 700;
  color: #4a9090;
  letter-spacing: 1.2px;
  padding: 8px 10px 4px;
  white-space: nowrap;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px;
  border-radius: 8px;
  text-decoration: none;
  color: #99cece;
  font-size: 13.5px;
  font-weight: 500;
  transition: all 0.15s;
  white-space: nowrap;
  margin-bottom: 2px;
}

.nav-item:hover { background: rgba(255,255,255,0.08); color: #fff; }
.nav-item--active { background: #0d9488 !important; color: #fff !important; }

.nav-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  width: 18px;
}

.nav-label { overflow: hidden; text-overflow: ellipsis; }

/* Footer */
.sidebar-footer {
  padding: 12px 10px;
  border-top: 1px solid rgba(255,255,255,0.07);
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}

.sidebar-user {
  display: flex;
  align-items: center;
  gap: 10px;
  flex: 1;
  overflow: hidden;
}

.user-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: #0d9488;
  color: white;
  font-size: 12px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.user-info { display: flex; flex-direction: column; overflow: hidden; }
.user-name { font-size: 13px; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.user-role { font-size: 10px; color: #99cece; white-space: nowrap; }

.logout-btn {
  background: none;
  border: none;
  color: #99cece;
  cursor: pointer;
  padding: 6px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  flex-shrink: 0;
  transition: all 0.15s;
}
.logout-btn:hover { background: rgba(255,255,255,0.08); color: #ef4444; }

/* ─── Main ──────────────────────────────────────────────────── */
.main-wrapper { flex: 1; display: flex; flex-direction: column; min-width: 0; }

.topbar {
  height: 56px;
  background: #fff;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 0 24px;
  position: sticky;
  top: 0;
  z-index: 50;
  flex-shrink: 0;
}

.collapse-btn {
  background: none;
  border: none;
  color: #64748b;
  cursor: pointer;
  padding: 6px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  transition: background 0.15s;
}
.collapse-btn:hover { background: #f1f5f9; color: #0f4f4f; }

.breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 13px; flex: 1; }
.breadcrumb-root    { color: #94a3b8; font-weight: 500; }
.breadcrumb-sep     { color: #cbd5e1; }
.breadcrumb-current { color: #0f4f4f; font-weight: 600; }

.topbar-right { display: flex; align-items: center; gap: 12px; }

/* ✅ Badge rôle dans la topbar */
.role-badge {
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
}
.role-admin   { background: #ede9fe; color: #6d28d9; }
.role-analyst { background: #e0f2fe; color: #0369a1; }

.online-badge {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: #16a34a;
  font-weight: 500;
}

.online-dot {
  width: 7px;
  height: 7px;
  background: #16a34a;
  border-radius: 50%;
  animation: pulse-green 2s ease-in-out infinite;
}

@keyframes pulse-green {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.4; }
}

.topbar-date { font-size: 12px; color: #94a3b8; }

.page-content { flex: 1; padding: 28px; overflow-y: auto; }

/* ─── Collapsed ─────────────────────────────────────────────── */
.sidebar.collapsed .brand-text,
.sidebar.collapsed .nav-label,
.sidebar.collapsed .user-info,
.sidebar.collapsed .nav-section-label { display: none; }

.sidebar.collapsed .nav-item { justify-content: center; padding: 10px; }
.sidebar.collapsed .sidebar-brand { justify-content: center; padding: 20px 8px 18px; }
.sidebar.collapsed .sidebar-user { justify-content: center; }
.app-shell {
  display: flex;
  min-height: 100vh;
  background: #f0f3f8;
  position: relative; /* ✅ important */
}
</style>