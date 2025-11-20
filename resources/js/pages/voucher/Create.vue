<script setup>
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import Textarea from 'primevue/textarea'
import { useRouter } from 'vue-router'
import { ref } from 'vue'
import { useToast } from 'primevue/usetoast'
import { createVoucher } from '../../../services/voucherService'
import InputNumber from 'primevue/inputnumber'

const router = useRouter()
const loading = ref(false)
const toast = useToast()

const voucher = ref({
  code: '',
  name: '',
  conditions: {
    min_tables: null
  },
  description: '',
  is_active: null,
})

const statusList = ref([
  { id: 0, label: 'Không hoạt động' },
  { id: 1, label: 'Hoạt động' }
])

const handleCreate = async () => {
  loading.value = true
  try {
    await createVoucher(voucher.value)
    toast.add({
      severity: 'success',
      summary: 'Thành công',
      detail: 'Tạo voucher thành công',
      life: 3000
    })
    router.push('/voucher')
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Lỗi',
      detail: error.response?.data?.message || 'Có lỗi xảy ra',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900 p-6">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section with Enhanced Design -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 mb-6">
        <div class="flex items-center gap-4">
          <button
            @click="router.push('/voucher')"
            class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500 flex items-center justify-center shadow-lg transition-all duration-200 hover:scale-105"
            aria-label="Quay lại"
          >
            <i class="pi pi-arrow-left text-gray-700 dark:text-gray-200 text-lg"></i>
          </button>
          
          <div class="flex items-center gap-3 flex-1">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
              <i class="pi pi-gift text-white text-xl"></i>
            </div>
            <div>
              <h1 class="font-bold text-3xl bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent">
                Tạo Voucher Mới
              </h1>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Nhập thông tin voucher mới vào hệ thống
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Card -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Card Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                  <i class="pi pi-file-edit text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                  <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Thông tin Voucher
                  </h2>
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    Điền đầy đủ thông tin bên dưới
                  </p>
                </div>
              </div>
            </div>

            <!-- Form Content -->
            <form @submit.prevent="handleCreate" class="p-8">
              <div class="space-y-8">
                <!-- Grid 2 cột -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Mã Voucher -->
                  <div class="space-y-2">
                    <label for="code" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                      <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <i class="pi pi-tag text-blue-600 dark:text-blue-400 text-xs"></i>
                      </div>
                      <span>Mã Voucher</span>
                      <span class="text-red-500">*</span>
                    </label>
                    <InputText
                      v-model="voucher.code"
                      id="code"
                      type="text"
                      placeholder="VD: BACKDROP, TABLE_GIFT"
                      class="w-full !py-3 !px-4 !rounded-xl !border-2 !border-gray-200 dark:!border-gray-600 focus:!border-indigo-500 dark:focus:!border-indigo-400 !shadow-sm"
                    />
                  </div>

                  <!-- Trạng thái -->
                  <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                      <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <i class="pi pi-check-circle text-green-600 dark:text-green-400 text-xs"></i>
                      </div>
                      <span>Trạng thái</span>
                      <span class="text-red-500">*</span>
                    </label>
                    <Dropdown
                      v-model="voucher.is_active"
                      :options="statusList"
                      placeholder="Chọn trạng thái"
                      option-label="label"
                      option-value="id"
                      class="w-full custom-dropdown"
                    />
                  </div>

                  <!-- Tên Voucher -->
                  <div class="space-y-2 md:col-span-2">
                    <label for="name" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                      <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <i class="pi pi-bookmark text-purple-600 dark:text-purple-400 text-xs"></i>
                      </div>
                      <span>Tên Voucher</span>
                      <span class="text-red-500">*</span>
                    </label>
                    <InputText
                      v-model="voucher.name"
                      id="name"
                      type="text"
                      placeholder="VD: Backdrop chụp hình, Tặng 1 bàn tiệc"
                      class="w-full !py-3 !px-4 !rounded-xl !border-2 !border-gray-200 dark:!border-gray-600 focus:!border-indigo-500 dark:focus:!border-indigo-400 !shadow-sm"
                    />
                  </div>

                  <!-- Điều kiện -->
                  <div class="space-y-2 md:col-span-2">
                    <label for="condition" class="flex items-center gap-2 text-sm foncreatet-semibold text-gray-700 dark:text-gray-300">
                      <div class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                        <i class="pi pi-list text-orange-600 dark:text-orange-400 text-xs"></i>
                      </div>
                      <span>Điều kiện</span>
                      <span class="text-red-500">*</span>
                    </label>
                    <InputNumber
                      v-model="voucher.conditions.min_tables"
                      :min="1"
                      id="condition"
                      type="text"
                      placeholder="VD: nhập số 50, 60"
                      class="w-full !py-3 !px-4 !rounded-xl !border-2 !border-gray-200 dark:!border-gray-600 focus:!border-indigo-500 dark:focus:!border-indigo-400 !shadow-sm"
                    />
                  </div>

                  <!-- Mô tả -->
                  <div class="space-y-2 md:col-span-2">
                    <label for="description" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                      <div class="w-8 h-8 rounded-lg bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center">
                        <i class="pi pi-align-left text-teal-600 dark:text-teal-400 text-xs"></i>
                      </div>
                      <span>Mô tả</span>
                      <span class="text-red-500">*</span>
                    </label>
                    <Textarea
                      v-model="voucher.description"
                      id="description"
                      rows="4"
                      placeholder="VD: Voucher tặng backdrop tiệc cưới, sinh nhật, sự kiện..."
                      class="w-full !py-3 !px-4 !rounded-xl !border-2 !border-gray-200 dark:!border-gray-600 focus:!border-indigo-500 dark:focus:!border-indigo-400 !shadow-sm resize-none"
                    />
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                  <button
                    type="button"
                    @click="router.push('/voucher')"
                    class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md"
                  >
                    <i class="pi pi-times mr-2"></i>
                    Hủy bỏ
                  </button>
                  <button
                    type="submit"
                    :disabled="loading"
                    class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl hover:opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <i class="pi pi-check mr-2"></i>
                    {{ loading ? 'Đang tạo...' : 'Tạo voucher' }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Info Sidebar -->
        <div class="lg:col-span-1 space-y-6">
          <!-- Help Card -->
          <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-blue-200 dark:border-blue-800">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-500 dark:bg-blue-600 flex items-center justify-center shadow-md">
                  <i class="pi pi-info-circle text-white"></i>
                </div>
                <h3 class="font-semibold text-blue-900 dark:text-blue-300">
                  Hướng dẫn
                </h3>
              </div>
            </div>
            <div class="p-6">
              <ul class="space-y-4">
                <li class="flex items-start gap-3">
                  <div class="w-6 h-6 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="pi pi-check text-indigo-600 dark:text-indigo-400 text-xs"></i>
                  </div>
                  <span class="text-sm text-gray-700 dark:text-gray-300">
                    Nhập đầy đủ thông tin có dấu <span class="text-red-500 font-semibold">*</span>
                  </span>
                </li>
                <li class="flex items-start gap-3">
                  <div class="w-6 h-6 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="pi pi-check text-green-600 dark:text-green-400 text-xs"></i>
                  </div>
                  <span class="text-sm text-gray-700 dark:text-gray-300">
                    Mã voucher phải là <span class="font-semibold">duy nhất</span>
                  </span>
                </li>
                <li class="flex items-start gap-3">
                  <div class="w-6 h-6 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="pi pi-check text-purple-600 dark:text-purple-400 text-xs"></i>
                  </div>
                  <span class="text-sm text-gray-700 dark:text-gray-300">
                    Điều kiện áp dụng voucher cần rõ ràng
                  </span>
                </li>
                <li class="flex items-start gap-3">
                  <div class="w-6 h-6 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="pi pi-check text-orange-600 dark:text-orange-400 text-xs"></i>
                  </div>
                  <span class="text-sm text-gray-700 dark:text-gray-300">
                    Mô tả chi tiết để khách hàng dễ hiểu
                  </span>
                </li>
                <li class="flex items-start gap-3">
                  <div class="w-6 h-6 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="pi pi-check text-purple-600 dark:text-purple-400 text-xs"></i>
                  </div>
                  <span class="text-sm text-gray-700 dark:text-gray-300">
                    Voucher chỉ cần nhập số
                  </span>
                </li>
              </ul>
            </div>
          </div>

          <!-- Voucher Examples Card -->
          <div class="bg-gradient-to-br from-pink-500 via-purple-500 to-indigo-500 rounded-2xl shadow-xl p-6 text-white">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                <i class="pi pi-gift text-white"></i>
              </div>
              <h3 class="font-semibold">Ví dụ Voucher</h3>
            </div>
            <div class="space-y-3">
              <div class="p-3 bg-white/10 backdrop-blur-sm rounded-lg">
                <div class="text-xs opacity-80 mb-1">Mã: BACKDROP</div>
                <div class="font-semibold text-sm">Backdrop chụp hình</div>
                <div class="text-xs opacity-90 mt-1"> 60 bàn trở lên</div>
              </div>
              <div class="p-3 bg-white/10 backdrop-blur-sm rounded-lg">
                <div class="text-xs opacity-80 mb-1">Mã: TABLE_GIFT</div>
                <div class="font-semibold text-sm">Tặng 1 bàn tiệc</div>
                <div class="text-xs opacity-90 mt-1"> 50 bàn trở lên</div>
              </div>
              <div class="p-3 bg-white/10 backdrop-blur-sm rounded-lg">
                <div class="text-xs opacity-80 mb-1">Mã: FREE_MC</div>
                <div class="font-semibold text-sm">Miễn phí MC</div>
                <div class="text-xs opacity-90 mt-1"> 60 bàn trở lên</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.custom-dropdown :deep(.p-dropdown) {
  @apply !py-3 !px-4 !rounded-xl !border-2 !border-gray-200 dark:!border-gray-600 !shadow-sm;
}

.custom-dropdown :deep(.p-dropdown:focus) {
  @apply !border-indigo-500 dark:!border-indigo-400;
}

.custom-dropdown :deep(.p-dropdown-trigger) {
  @apply !w-10;
}
</style>