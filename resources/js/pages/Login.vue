<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { Icon } from '@iconify/vue'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'
import { USER_ROLES } from '@/utils/constants.js'
import { login, getCurrentUser } from '../../services/authService'

const router = useRouter()
const toast = useToast()

const tel = ref('')
const password = ref('')
const loading = ref(false)

const handleLogin = async () => {
  loading.value = true
  try {
    const resp = await login(tel.value, password.value)
    if (resp.data.status === 'OK') {
      const currentUser = await getCurrentUser()
      if (currentUser?.role == USER_ROLES.REPORT_VIEWER) {
        router.push('/reports')
      } else {
        router.push('/dashboard')
      }
    }
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Lỗi',
      detail: error.response?.data?.message || 'Đăng nhập thất bại',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-900 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] opacity-40"></div>
    
    <!-- Floating Orbs -->
    <div class="absolute top-20 left-20 w-72 h-72 bg-blue-500/20 rounded-full filter blur-3xl animate-pulse"></div>
    <div class="absolute bottom-20 right-20 w-96 h-96 bg-purple-500/20 rounded-full filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    
    <div class="max-w-md w-full relative z-10">
      <!-- Logo & Header Section -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 rounded-3xl shadow-2xl mb-6 relative transform hover:scale-105 transition-transform duration-300">
          <div class="absolute inset-0 bg-white/30 rounded-3xl blur-2xl animate-pulse"></div>
          <Icon
            icon="heroicons:building-storefront"
            class="h-12 w-12 text-white relative z-10 drop-shadow-lg"
          />
        </div>
        <h1 class="text-5xl font-bold text-white mb-3 tracking-tight">
          Thanh An Hội
        </h1>
        <p class="text-blue-200/80 text-base font-medium">
          Hệ thống quản lý tiệc cưới
        </p>
      </div>

      <!-- Login Card -->
      <div class="bg-white/10 backdrop-blur-2xl border border-white/20 rounded-3xl p-8 shadow-2xl transform hover:shadow-3xl transition-all duration-300">
        <div class="mb-6">
          <h2 class="text-2xl font-bold text-white text-center mb-2">
            Đăng nhập
          </h2>
          <p class="text-blue-200/60 text-sm text-center">
            Vui lòng nhập thông tin của bạn
          </p>
        </div>

        <form
          class="space-y-5"
          @submit.prevent="handleLogin"
        >
          <!-- Phone Input -->
          <div class="space-y-2">
            <label
              for="tel"
              class="block text-sm font-semibold text-blue-100 mb-2"
            >
              <div class="flex items-center gap-2">
                <Icon icon="heroicons:phone" class="w-4 h-4" />
                <span>Số điện thoại</span>
              </div>
            </label>
            <div class="relative group">
              <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl opacity-0 group-hover:opacity-20 blur transition-opacity duration-300"></div>
              <InputText
                id="tel"
                v-model="tel"
                type="tel"
                required
                class="w-full !px-4 !py-4 !bg-white/10 !border-2 !border-white/20 !rounded-xl !text-white placeholder:!text-blue-200/40 focus:!outline-none focus:!ring-2 focus:!ring-blue-400 focus:!border-blue-400/50 focus:!bg-white/15 transition-all duration-200 relative"
                placeholder="Nhập số điện thoại của bạn"
              />
            </div>
          </div>

          <!-- Password Input -->
          <div class="space-y-2">
            <label
              for="password"
              class="block text-sm font-semibold text-blue-100 mb-2"
            >
              <div class="flex items-center gap-2">
                <Icon icon="heroicons:lock-closed" class="w-4 h-4" />
                <span>Mật khẩu</span>
              </div>
            </label>
            <div class="relative group">
              <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl opacity-0 group-hover:opacity-20 blur transition-opacity duration-300"></div>
              <Password
                id="password"
                v-model="password"
                required
                class="w-full [&_input]:w-full [&_input]:!px-4 [&_input]:!py-4 [&_input]:!bg-white/10 [&_input]:!border-2 [&_input]:!border-white/20 [&_input]:!rounded-xl [&_input]:!text-white [&_input]:placeholder:!text-blue-200/40 [&_input]:focus:!outline-none [&_input]:focus:!ring-2 [&_input]:focus:!ring-blue-400 [&_input]:focus:!border-blue-400/50 [&_input]:focus:!bg-white/15 [&_input]:transition-all [&_input]:duration-200 [&_button]:!bg-transparent [&_button]:!border-0 [&_button:hover]:!bg-white/10 [&_button]:!rounded-lg [&_button]:!transition-all [&_svg]:!text-blue-200 [&_svg]:!w-5 [&_svg]:!h-5 relative"
                input-class="w-full"
                placeholder="Nhập mật khẩu của bạn"
                :feedback="false"
                toggle-mask
              />
            </div>
          </div>

          <!-- Submit Button -->
          <div class="pt-4">
            <Button
              type="submit"
              :loading="loading"
              class="w-full !bg-gradient-to-r !from-blue-500 !via-indigo-500 !to-purple-600 hover:!from-blue-600 hover:!via-indigo-600 hover:!to-purple-700 !text-white !font-bold !py-4 !rounded-xl !shadow-2xl hover:!shadow-blue-500/50 !transform hover:!scale-[1.02] active:!scale-[0.98] !transition-all !duration-200 !border-0 relative overflow-hidden group"
              size="large"
            >
              <span class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
              <span class="relative flex items-center justify-center gap-2 text-base">
                <Icon v-if="!loading" icon="heroicons:arrow-right-on-rectangle" class="w-5 h-5" />
                <span>{{ loading ? 'Đang đăng nhập...' : 'Đăng nhập' }}</span>
              </span>
            </Button>
          </div>

          <!-- Divider -->
          <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-white/20"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-4 bg-transparent text-blue-200/60">
                Hệ thống quản lý
              </span>
            </div>
          </div>
        </form>
      </div>

      <!-- Footer -->
      <div class="mt-8 text-center space-y-3">
        <div class="flex items-center justify-center gap-2 text-sm text-blue-200/60">
          <Icon icon="heroicons:shield-check" class="w-4 h-4" />
          <span>Bảo mật & An toàn</span>
        </div>
        <p class="text-sm text-blue-200/40">
          © 2025 Thanh An Hội. All rights reserved.
        </p>
      </div>
    </div>
  </div>
  <Toast />
</template>

<style scoped>
@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-20px);
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

/* Custom scrollbar for password field */
:deep(.p-password-panel) {
  background: rgba(255, 255, 255, 0.1) !important;
  backdrop-filter: blur(10px) !important;
  border: 1px solid rgba(255, 255, 255, 0.2) !important;
  border-radius: 12px !important;
}

/* Button loading state */
:deep(.p-button-loading-icon) {
  color: white !important;
}

/* Input autofill styling */
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus {
  -webkit-text-fill-color: white !important;
  -webkit-box-shadow: 0 0 0px 1000px rgba(255, 255, 255, 0.1) inset !important;
  transition: background-color 5000s ease-in-out 0s;
}
</style>