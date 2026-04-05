<!-- src/components/ThemeToggle.vue -->
<template>
  <button class="theme-toggle" @click="toggleTheme" :title="isDark ? 'Mode clair' : 'Mode sombre'">
    <span v-if="isDark">☀️</span>
    <span v-else>🌙</span>
  </button>
</template>

<script setup>
import { useTheme } from "@/composables/useTheme"
const { isDark, toggleTheme } = useTheme()
</script>

<style scoped>
.theme-toggle {
  background: none;
  border: 2px solid var(--border-color);
  border-radius: 50%;
  width: 42px;
  height: 42px;
  font-size: 18px;
  cursor: pointer;
  transition: 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.theme-toggle:hover {
  border-color: var(--btn-primary);
  transform: rotate(20deg);
}
</style>