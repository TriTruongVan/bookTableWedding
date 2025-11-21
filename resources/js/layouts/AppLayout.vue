<script setup>
import Button from "primevue/button";
import { useRoute, useRouter } from "vue-router";
import Avatar from 'primevue/avatar';
import Menu from 'primevue/menu';
import { computed, onMounted, ref } from "vue";
import { getCurrentUser, logout } from "../../services/authService";
import { USER_ROLES } from "@/utils/constants";
import { Icon } from "@iconify/vue";

const route = useRoute();
const router = useRouter();
const currentUser = computed(() => getCurrentUser())
const sidebarCollapsed = ref(false)
const userMenuRef = ref()
const sidebarHovered = ref(false)

const menuItems = [
  { label: 'Bảng điều khiển', icon: 'heroicons:home', route: '/dashboard' },
  { label: 'Khách hàng', icon: 'heroicons:user', route: '/customer' },
  { label: 'Mã khuyến mãi', icon: 'heroicons:receipt-percent', route: '/voucher' },
  { label: 'Nhân viên', icon: 'heroicons:user-group', route: '/staff' },
]
const filteredMenuItems = computed(() =>{
  const showForReportViewer = [
    'Reports',
  ]

  return menuItems.filter(item => {
    if (currentUser.value?.role === USER_ROLES.REPORT_VIEWER) {
      return showForReportViewer.includes(item.label)
    }

    return true
  })
})

const isActive = (targetRoute) => {
  const currentPath = route.path;
  return currentPath === targetRoute || currentPath.startsWith(targetRoute + '/');
};

const userMenuItems = computed(() => {
  let listMenu = [
    {
      label: 'Profile',
      icon: 'pi pi-user',
      command: () => router.push('/profile')
    },
    {
      separator: true
    },
    {
      label: 'Logout',
      icon: 'pi pi-sign-out',
      command: handleLogout
    }
  ]

  if (currentUser.value?.role == USER_ROLES.REPORT_VIEWER) {
    listMenu = listMenu.filter(item => item.label == 'Logout')
  }

  return listMenu
})

const sidebarWidth = computed(() => {
  if (sidebarCollapsed.value && !sidebarHovered.value) {
    return 'w-16'
  }
  return 'w-64'
})

const shouldShowLabel = computed(() => {
  return !sidebarCollapsed.value || sidebarHovered.value
})

function toggleUserMenu(event) {
  userMenuRef.value.toggle(event)
}

function toggleSidebar() {
  sidebarCollapsed.value = !sidebarCollapsed.value
}

function toggleDarkMode() {
  const html = document.documentElement
  const isDark = html.classList.contains('dark')

  if (isDark) {
    html.classList.remove('dark')
    localStorage.setItem('darkMode', 'false')
  } else {
    html.classList.add('dark')
    localStorage.setItem('darkMode', 'true')
  }
}

async function handleLogout() {
  try {
    await logout()
    router.push('/login')
  } catch (error) {
    logger.error('Logout error:', error)
  }
}
</script>

<template>
  <div class="h-screen flex flex-col bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:via-slate-800 dark:to-gray-900">
    <!-- Top Navigation with Glass Effect -->
    <nav class="backdrop-blur-lg bg-white/90 dark:bg-gray-800/90 shadow-lg border-b border-gray-200/50 dark:border-gray-700/50 px-6 py-4 sticky top-0 z-20">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <Button 
            icon="pi pi-bars"
            class="!w-10 !h-10 hover:scale-105 transition-transform hover:bg-gray-100 dark:hover:bg-gray-700"
            text
            rounded
            @click="toggleSidebar"
          />
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
              <span class="text-white font-bold text-lg">TH</span>
            </div>
            <h1 class="text-xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
              Thanh An Hội
            </h1>
          </div>
        </div>
        
        <div class="flex items-center gap-3">
          <Button 
            v-tooltip.bottom="'Chuyển chế độ tối'" 
            icon="pi pi-moon" 
            text 
            rounded
            class="!w-10 !h-10 hover:scale-105 transition-transform hover:bg-gray-100 dark:hover:bg-gray-700"
            @click="toggleDarkMode"
          />
          
          <div class="relative">
            <Button 
              v-tooltip.bottom="'Thông báo'" 
              icon="pi pi-bell" 
              text 
              rounded
              class="!w-10 !h-10 hover:scale-105 transition-transform relative hover:bg-gray-100 dark:hover:bg-gray-700"
              @click="router.push('/dashboard')" 
            />
            <span class="absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-rose-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-lg animate-pulse">
              1
            </span>
          </div>
          
          <div class="hidden md:flex items-center gap-3 px-4 py-2 bg-gray-100 dark:bg-gray-700/70 rounded-full">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
              Xin chào, <span class="text-indigo-600 dark:text-indigo-400">Thanh An Hội</span>
            </span>
          </div>
          
          <div class="relative group cursor-pointer" @click="toggleUserMenu">
              <Avatar 
                image="images/logo.png"
                size="large"
                shape="circle"
                class="hover:scale-105 transition-transform"
              />
          </div>
          <Menu ref="userMenuRef" :model="userMenuItems" popup class="!mt-2" />
        </div>
      </div>
    </nav>

    <div class="flex flex-1 overflow-hidden">
      <!-- Enhanced Sidebar -->
      <aside
        :class="[
          'transition-all duration-300 ease-in-out',
          'backdrop-blur-lg bg-white/90 dark:bg-gray-800/90',
          'border-r border-gray-200/50 dark:border-gray-700/50',
          'shadow-xl flex-shrink-0 relative z-10',
          sidebarWidth
        ]"
        @mouseenter="sidebarHovered = true"
        @mouseleave="sidebarHovered = false"
      >
        <div class="flex flex-col h-full pt-6 pb-4 overflow-y-auto">
          <div class="flex-grow flex flex-col">
            <nav class="flex-1 px-3 space-y-2">
              <router-link
                v-for="item in filteredMenuItems"
                :key="item.route"
                :to="item.route"
                class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:scale-105"
                :class="isActive(item.route)
                  ? 'bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white shadow-lg shadow-purple-500/30'
                  : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/70 hover:shadow-md'"
                :title="!shouldShowLabel ? item.label : ''"
              >
                <Icon
                  :icon="item.icon"
                  class="h-6 w-6 flex-shrink-0 transition-transform group-hover:scale-110"
                  :class="shouldShowLabel ? 'mr-3' : 'mx-auto'"
                />
                <span
                  v-if="shouldShowLabel"
                  class="truncate font-semibold"
                >{{ item.label }}</span>
              </router-link>
            </nav>
          </div>
        </div>
      </aside>

      <!-- Main Content Area -->
      <div class="flex flex-col flex-1 overflow-auto">
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
          <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <router-view v-slot="{ Component }">
              <transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0 translate-y-4"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
              >
                <component :is="Component" />
              </transition>
            </router-view>
          </div>
        </main>
      </div>
    </div>

    <!-- Offline Modal with Enhanced Design -->
    <div
      id="offlineModal"
      class="hidden fixed top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-amber-400 to-orange-500 text-white px-6 py-3 rounded-full shadow-2xl justify-center items-center gap-2 z-50 animate-bounce"
    >
      <Icon icon="heroicons:wifi-slash" class="h-5 w-5" />
      <span class="font-semibold">Không có kết nối Internet</span>
    </div>
  </div>
  
  <Toast />
  <ConfirmDialog />
</template>
