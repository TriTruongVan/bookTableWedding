<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { Icon } from '@iconify/vue'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
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
      summary: ('Lỗi'),
      detail: error.response?.data?.message || ('LoginFailed'),
      life: 3000
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] opacity-40"></div>
    <div class="max-w-md w-full relative z-10">
      <!-- Header Section -->
      <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl shadow-2xl mb-6 relative">
          <div class="absolute inset-0 bg-white/20 rounded-3xl blur-xl"></div>
          <Icon
            icon="heroicons:building-storefront"
            class="h-10 w-10 text-white relative z-10"
          />
        </div>
        <h2 class="text-4xl font-bold text-white mb-2 tracking-tight">
          Thanh An Hội
        </h2>
        <p class="text-blue-200 text-sm">
          Dịch vụ quản lý hệ thống - ẩm thực tiệc cưới
        </p>
      </div>
      <!-- Login Card -->
      <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl">
        <form
          class="space-y-6"
          @submit.prevent="handleLogin"
        >
          <!-- Phone Input -->
          <div>
            <label
              for="tel"
              class="block text-sm font-medium text-blue-100 mb-2"
            >
              Số điện thoại
            </label>
            <InputText
              id="tel"
              v-model="tel"
              type="tel"
              required
              class="w-full !px-4 !py-3.5 !bg-blue-500/20 !border !border-blue-400/30 !rounded-xl !text-white placeholder:!text-blue-200/50 focus:!outline-none focus:!ring-2 focus:!ring-blue-400 focus:!border-transparent focus:!bg-blue-500/30 transition-all duration-200"
              placeholder="Nhập số điện thoại"
            />
          </div>

          <!-- Password Input -->
          <div>
            <label
              for="password"
              class="block text-sm font-medium text-blue-100 mb-2"
            >
              Mật khẩu
            </label>
            <Password
              id="password"
              v-model="password"
              required
              class="w-full [&_input]:w-full [&_input]:!px-4 [&_input]:!py-3.5 [&_input]:!bg-blue-500/20 [&_input]:!border [&_input]:!border-blue-400/30 [&_input]:!rounded-xl [&_input]:!text-white [&_input]:placeholder:!text-blue-200/50 [&_input]:focus:!outline-none [&_input]:focus:!ring-2 [&_input]:focus:!ring-blue-400 [&_input]:focus:!border-transparent [&_input]:focus:!bg-blue-500/30 [&_input]:transition-all [&_input]:duration-200 [&_button]:bg-transparent [&_button]:border-0 [&_button:hover]:bg-white/5 [&_svg]:text-blue-200"
              input-class="w-full"
              placeholder="Nhập mật khẩu"
              :feedback="false"
              toggle-mask
            />
          </div>

          <!-- Submit Button -->
          <div class="pt-2">
            <Button
              type="submit"
              :loading="loading"
              class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-emerald-500/50 transform hover:scale-[1.02] transition-all duration-200"
              size="large"
            >
              <span class="text-base">Đăng nhập</span>
            </Button>
          </div>
        </form>
      </div>

      <!-- Footer -->
      <p class="mt-8 text-center text-sm text-blue-200/60">
        © 2025 Thanh An Hội
      </p>
    </div>
  </div>
  <Toast/>
</template>