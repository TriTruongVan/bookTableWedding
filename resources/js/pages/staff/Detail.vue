<script setup lang="ts">
import Card from 'primevue/card';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getAllWards } from '../../../services/userService'
import { getStaffById, updateStaff } from '../../../services/staffService';

const router = useRouter()
const route = useRoute();
const loading = ref(false)
const toast = useToast()
const wardsList = ref([])
const staff = ref({
  name: '',
  tel: '',
  street: '',
  ward: null,
  status:null,
})

onMounted(async () => {
  await loadStaff()
  await loadWards()
})

const loadWards = async () => {
  loading.value = true
  try {
    wardsList.value = (await getAllWards()).data.data.wards
    if (!wardsList.value) {
      toast.add({
        severity: 'error',
        summary: 'Lỗi',
        detail: 'Không tìm thấy danh sách phường',
        life: 3000
      })
      return
    }
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Lỗi',
      detail: 'Cập nhật địa chỉ thất bại',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}

const roleList = ref([
  { id: "0", label: 'Bếp' },
  { id: "1", label: 'Phục vụ' }
])

const handleSubmit = async () => {
  loading.value = true
  try {
    await updateStaff(route.params.id, staff.value)
    toast.add({
      severity: 'success',
      summary: 'Success',
      detail: 'Cập nhật nhân viên thành công',
      life: 3000
    })

    router.push('/staff')
  } catch (error: any) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Cập nhật nhân viên thất bại',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}

const loadStaff = async () => {
  loading.value = true
  try {
    const response = await getStaffById(route.params.id)
    staff.value = response.data.data || staff.value
    if (!staff.value) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Không tìm thấy nhân viên',
        life: 3000
      })
      router.push('/staff')
    }
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Tải thông tin nhân viên thất bại',
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
            @click="router.push('/staff')"
            class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500 flex items-center justify-center shadow-lg transition-all duration-200 hover:scale-105"
            aria-label="Quay lại"
          >
            <i class="pi pi-arrow-left text-gray-700 dark:text-gray-200 text-lg"></i>
          </button>
          
          <div class="flex items-center gap-3 flex-1">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
              <i class="pi pi-user-edit text-white text-xl"></i>
            </div>
            <div>
              <h1 class="font-bold text-3xl bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent">
                Cập nhật nhân viên
              </h1>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Chỉnh sửa thông tin nhân viên trong hệ thống
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
                    Thông tin nhân viên
                  </h2>
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    Chỉnh sửa các thông tin bên dưới
                  </p>
                </div>
              </div>
            </div>

            <!-- Form Content -->
            <form @submit.prevent="handleSubmit" class="p-8">
              <div class="space-y-8">
                <!-- Họ và tên - Full Width -->
                <div class="space-y-2">
                  <label for="name" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                      <i class="pi pi-user text-emerald-600 dark:text-emerald-400 text-xs"></i>
                    </div>
                    <span>Họ và tên</span>
                    <span class="text-red-500">*</span>
                  </label>
                  <InputText
                    v-model="staff.name"
                    id="name"
                    type="text"
                    placeholder="Nhập họ và tên đầy đủ"
                    class="w-full !py-3 !px-4 !rounded-xl !border-2 !border-gray-200 dark:!border-gray-600 focus:!border-indigo-500 dark:focus:!border-indigo-400 !shadow-sm"
                  />
                </div>

                <!-- Grid 2 cột -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Đường -->
                  <div class="space-y-2">
                    <label for="street" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                      <div class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                        <i class="pi pi-map-marker text-orange-600 dark:text-orange-400 text-xs"></i>
                      </div>
                      <span>Đường</span>
                      <span class="text-red-500">*</span>
                    </label>
                    <InputText
                      v-model="staff.street"
                      id="street"
                      type="text"
                      placeholder="Nhập tên đường"
                      class="w-full !py-3 !px-4 !rounded-xl !border-2 !border-gray-200 dark:!border-gray-600 focus:!border-indigo-500 dark:focus:!border-indigo-400 !shadow-sm"
                    />
                  </div>

                  <!-- Xã / Phường -->
                  <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                      <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <i class="pi pi-building text-blue-600 dark:text-blue-400 text-xs"></i>
                      </div>
                      <span>Xã / Phường</span>
                      <span class="text-red-500">*</span>
                    </label>
                    <Dropdown
                      v-model="staff.ward"
                      :options="wardsList"
                      placeholder="Chọn xã / phường"
                      option-label="label"
                      option-value="id"
                      class="w-full custom-dropdown"
                    />
                  </div>

                  <!-- Số điện thoại -->
                  <div class="space-y-2">
                    <label for="tel" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                      <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <i class="pi pi-phone text-green-600 dark:text-green-400 text-xs"></i>
                      </div>
                      <span>Số điện thoại</span>
                      <span class="text-red-500">*</span>
                    </label>
                    <InputText
                      v-model="staff.tel"
                      id="tel"
                      type="text"
                      placeholder="Nhập số điện thoại (10 số)"
                      class="w-full !py-3 !px-4 !rounded-xl !border-2 !border-gray-200 dark:!border-gray-600 focus:!border-indigo-500 dark:focus:!border-indigo-400 !shadow-sm"
                    />
                  </div>

                  <!-- Hệ nhân viên -->
                  <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                      <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <i class="pi pi-users text-purple-600 dark:text-purple-400 text-xs"></i>
                      </div>
                      <span>Hệ nhân viên</span>
                      <span class="text-red-500">*</span>
                    </label>
                    <Dropdown
                      v-model="staff.status"
                      :options="roleList"
                      placeholder="Chọn hệ nhân viên"
                      class="w-full custom-dropdown"
                      option-label="label"
                      option-value="id"
                      :required="true"
                    />
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                  <button
                    type="button"
                    @click="router.push('/staff')"
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
                    {{ loading ? 'Đang cập nhật...' : 'Cập nhật nhân viên' }}
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
                    Kiểm tra lại <span class="font-semibold">tất cả thông tin</span> trước khi cập nhật
                  </span>
                </li>
                <li class="flex items-start gap-3">
                  <div class="w-6 h-6 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="pi pi-check text-green-600 dark:text-green-400 text-xs"></i>
                  </div>
                  <span class="text-sm text-gray-700 dark:text-gray-300">
                    Nhấn <span class="font-semibold">"Cập nhật nhân viên"</span> để lưu thông tin
                  </span>
                </li>
                <li class="flex items-start gap-3">
                  <div class="w-6 h-6 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="pi pi-check text-orange-600 dark:text-orange-400 text-xs"></i>
                  </div>
                  <span class="text-sm text-gray-700 dark:text-gray-300">
                    Các trường có dấu <span class="text-red-500 font-semibold">*</span> là bắt buộc
                  </span>
                </li>
                <li class="flex items-start gap-3">
                  <div class="w-6 h-6 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="pi pi-check text-purple-600 dark:text-purple-400 text-xs"></i>
                  </div>
                  <span class="text-sm text-gray-700 dark:text-gray-300">
                    Thay đổi sẽ được <span class="font-semibold">lưu ngay lập tức</span>
                  </span>
                </li>
              </ul>
            </div>
          </div>

          <!-- Customer Info Summary Card -->
          <div class="bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500 rounded-2xl shadow-xl p-6 text-white">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                <i class="pi pi-user text-white"></i>
              </div>
              <h3 class="font-semibold">Thông tin hiện tại</h3>
            </div>
            <div class="space-y-3">
              <div class="p-3 bg-white/10 backdrop-blur-sm rounded-lg">
                <div class="text-xs opacity-80 mb-1">Tên nhân viên</div>
                <div class="font-semibold truncate">{{ staff.name || '-' }}</div>
              </div>
              <div class="p-3 bg-white/10 backdrop-blur-sm rounded-lg">
                <div class="text-xs opacity-80 mb-1">Số điện thoại</div>
                <div class="font-semibold font-mono">{{ staff.tel || '-' }}</div>
              </div>
              <div class="p-3 bg-white/10 backdrop-blur-sm rounded-lg">
                <div class="text-xs opacity-80 mb-1">Loại nhân viên</div>
                <div class="font-semibold">
                  {{ staff.status === "1" ? 'Phục vụ' : staff.status === "0" ? 'Bếp' : '-' }}
                </div>
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