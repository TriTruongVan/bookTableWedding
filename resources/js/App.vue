<script setup lang="ts">
import { watch, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { isAuthenticated } from '../services/authService'
import AppLayout from './layouts/AppLayout.vue'

const router = useRouter()
const route = useRoute()

const isShopRoute = () => {
  return route.name === 'Shop' || route.name === 'ShopDetail'
}

const handleAuth = () => {
  const auth = isAuthenticated()
  if (isShopRoute()) return

  if (!auth && route.meta.requiresAuth) {
    router.push('/login')
  }
  if (auth && route.meta.requiresGuest) {
    router.push('/dashboard')
  }
}

onMounted(handleAuth)
watch(() => route.fullPath, handleAuth)
</script>

<template>
  <div id="app">
    <router-view v-if="['Login', 'Shop', 'ShopDetail'].includes(String($route.name))" />
    <AppLayout v-else-if="isAuthenticated()" />
  </div>
</template>
