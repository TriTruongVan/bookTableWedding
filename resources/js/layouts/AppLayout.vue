<script setup>
import Button from "primevue/button";
import { useRoute, useRouter } from "vue-router";
import Avatar from 'primevue/avatar';
import Menu from 'primevue/menu';
import { computed, ref } from "vue";
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
  { label: 'Dashboard', icon: 'heroicons:home', route: '/dashboard' },
]
const filteredMenuItems = computed(() =>{
  const showForSale = ['Dashboard']
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
  <div class="h-screen flex flex-col bg-gray-50 dark:bg-gray-900">
    <!-- Top Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-gray-200 dark:border-gray-700 px-4 lg:px-6 py-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <Button 
            icon="pi pi-bars"
            class="lg:mr-3 mr-3"
            text
            @click="toggleSidebar"
          />
          <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
            Thanh An Hội
          </h1>
        </div>
        <div class="flex items-center space-x-4">
          <Button v-tooltip="'Chuyển chế độ tối'" icon="pi pi-moon" text @click="toggleDarkMode"/>
          <div class="relative">
            <Button v-tooltip="'Notifications'" icon="pi pi-bell" text class="relative"
              @click="router.push('/dashboard')" />
            <span :class="[
              'absolute -top-1 -right-1 bg-red-500 text-white text-xs',
              ' rounded-full h-5 w-5 flex items-center justify-center font-bold',
            ]">
              1
            </span>
          </div>
          <span class="text-sm text-gray-600 dark:text-gray-300">
            Xin chào, Thanh An Hội
          </span>
          <Avatar 
            image="images/logo.png"
            class="mr-2" size="large"
            shape="circle"
            @click="toggleUserMenu"
          />
          <Menu ref="userMenuRef" :model="userMenuItems" popup />
        </div>
      </div>
    </nav>
    <div class="flex flex-grow-1 overflow-hidden">
      <aside
        :class="[
          'transition-all duration-300 ease-in-out bg-white dark:bg-gray-800',
          ' border-r border-gray-200 dark:border-gray-700 flex-shrink-0 relative z-10',
          sidebarWidth
        ]"
        @mouseenter="sidebarHovered = true"
        @mouseleave="sidebarHovered = false"
      >
        <div class="flex flex-col h-[calc(100vh-73px)] pt-5 pb-4 overflow-y-auto">
          <div class="flex-grow flex flex-col">
            <nav class="flex-1 px-2 space-y-1">
              <router-link
                v-for="item in filteredMenuItems"
                :key="item.route"
                :to="item.route"
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
                :class="isActive(item.route)
                  ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100'
                  : ['text-gray-600 dark:text-gray-300 hover:bg-gray-50 ',
                    'dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white']"
                :title="!shouldShowLabel ? item.label : ''"
              >
                <Icon
                  :icon="item.icon"
                  class="h-5 w-5 flex-shrink-0"
                  :class="shouldShowLabel ? 'mr-3' : 'mx-auto'"
                />
                <span
                  v-if="shouldShowLabel"
                  class="truncate"
                >{{ item.label }}</span>
              </router-link>
            </nav>
          </div>
        </div>
      </aside>
      <div class="flex flex-col flex-1 overflow-auto">
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
          <div>
            <div class="w-full mx-auto px-4 sm:px-6 md:px-8">
              <router-view />
            </div>
          </div>
        </main>
      </div>
    </div>
    <div
      id="offlineModal"
      class="hidden fixed top-0 right-0 left-0 bg-[#ffefbf] p-4 justify-center z-10"
    >
      <span> NoInternet </span>
    </div>
  </div>
  <Toast />
  <ConfirmDialog />
</template>