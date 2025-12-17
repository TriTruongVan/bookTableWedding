<script setup>
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getDishById, getDishGroup, updateDish } from '../../../services/dishService';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { useToast } from 'primevue/usetoast';
import Dropdown from 'primevue/dropdown';

const router = useRouter()
const route = useRoute()
const toast = useToast()
const loading = ref(false)
const groupDish = ref([])
const dish = ref({
  name: '',
  description: '',
  dish_group_id: '',
})

onMounted(async () => {
  await loadDish()
  await loadGroupDish()
})

const loadGroupDish = async () => {
  loading.value = true
  try {
    const resp = await getDishGroup()
    groupDish.value = resp.data.data.items
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Lỗi',
      detail: 'Cập nhật món ăn thất bại',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}

const loadDish = async () => {
  loading.value = true
  try {
    const resp = await getDishById(route.params.id)
    dish.value = resp.data.data || dish.value
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Tải thông tin món ăn thất bại',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}

const handleSubmit = async () => {
  loading.value = true
  try {
    await updateDish(route.params.id, dish.value)
    toast.add({
      severity: 'success',
      summary: 'Thành công',
      detail: 'Cập nhật món ăn thành công',
      life: 3000
    })
    router.push('/dish')
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Cập nhật món ăn thất bại',
      life: 3000
    })
  } finally {
    loading.value = false
  }
} 
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center gap-4">
          <button
            @click="router.push('/dish')"
            class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center transition-colors"
            aria-label="Quay lại"
          >
            <i class="pi pi-arrow-left text-gray-700 dark:text-gray-200"></i>
          </button>
          
          <div class="flex items-center gap-3 flex-1">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
              <i class="pi pi-pencil text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <div>
              <h1 class="font-bold text-2xl text-gray-900 dark:text-white">
                Cập nhật món ăn
              </h1>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Chỉnh sửa thông tin món ăn trong hệ thống
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Card -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <!-- Card Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center">
                  <i class="pi pi-file-edit text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                  <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Thông tin món ăn
                  </h2>
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    Chỉnh sửa các thông tin bên dưới
                  </p>
                </div>
              </div>
            </div>

            <!-- Form Content -->
            <form @submit.prevent="handleSubmit" class="p-6">
              <div class="space-y-6">
                <!-- Tên món ăn -->
                <div class="space-y-2">
                  <label for="name" class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="pi pi-tag text-gray-400"></i>
                    <span>Tên món ăn</span>
                    <span class="text-red-500">*</span>
                  </label>
                  <InputText
                    v-model="dish.name"
                    id="name"
                    type="text"
                    placeholder="Nhập tên món ăn"
                    class="w-full"
                  />
                </div>

                <!-- Danh mục -->
                <div class="space-y-2">
                  <label for="dish_group_name" class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="pi pi-list text-gray-400"></i>
                    <span>Danh mục</span>
                    <span class="text-red-500">*</span>
                  </label>
                  <Dropdown
                    v-model="dish.dish_group_id"
                    :options="groupDish"
                    optionLabel="name"
                    type="text"
                    placeholder="Nhập tên danh mục"
                    option-value="id"
                    class="w-full custom-dropdown"
                  />
                </div>

                <!-- Mô tả -->
                <div class="space-y-2">
                  <label for="description" class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="pi pi-align-left text-gray-400"></i>
                    <span>Mô tả</span>
                    <span class="text-red-500">*</span>
                  </label>
                  <Textarea
                    v-model="dish.description"
                    id="description"
                    rows="4"
                    placeholder="Nhập mô tả món ăn"
                    class="w-full"
                  />
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                  <button
                    type="button"
                    @click="router.push('/dish')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                  >
                    <i class="pi pi-times mr-2"></i>
                    Hủy bỏ
                  </button>
                  <button
                    type="submit"
                    :disabled="loading"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <i class="pi pi-check mr-2"></i>
                    {{ loading ? 'Đang cập nhật...' : 'Cập nhật món ăn' }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Info Sidebar -->
        <div class="lg:col-span-1 space-y-6">
          <!-- Help Card -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border-b border-blue-100 dark:border-blue-800 rounded-t-xl">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center">
                  <i class="pi pi-info-circle text-white text-sm"></i>
                </div>
                <h3 class="font-semibold text-blue-900 dark:text-blue-300">
                  Hướng dẫn
                </h3>
              </div>
            </div>
            <div class="p-4">
              <ul class="space-y-3">
                <li class="flex items-start gap-2">
                  <i class="pi pi-check-circle text-indigo-500 text-sm mt-0.5"></i>
                  <span class="text-sm text-gray-600 dark:text-gray-400">
                    Kiểm tra lại thông tin trước khi cập nhật
                  </span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="pi pi-check-circle text-green-500 text-sm mt-0.5"></i>
                  <span class="text-sm text-gray-600 dark:text-gray-400">
                    Các trường có dấu <span class="text-red-500 font-medium">*</span> là bắt buộc
                  </span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="pi pi-check-circle text-orange-500 text-sm mt-0.5"></i>
                  <span class="text-sm text-gray-600 dark:text-gray-400">
                    Thay đổi sẽ được lưu ngay lập tức
                  </span>
                </li>
              </ul>
            </div>
          </div>

          <!-- Customer Info Summary Card -->
          <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-sm p-5 text-white">
            <div class="flex items-center gap-2 mb-4">
              <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="pi pi-info text-white text-sm"></i>
              </div>
              <h3 class="font-semibold">Thông tin hiện tại</h3>
            </div>
            <div class="space-y-3">
              <div class="p-3 bg-white/10 backdrop-blur-sm rounded-lg">
                <div class="text-xs opacity-80 mb-1">Tên món ăn</div>
                <p class="font-medium">{{ dish.name || '---' }}</p>
              </div>
              <div class="p-3 bg-white/10 backdrop-blur-sm rounded-lg">
                <div class="text-xs opacity-80 mb-1">Danh mục</div>
                <p class="font-medium">{{ dish.dish_group_name || '---' }}</p>
              </div>
              <div class="p-3 bg-white/10 backdrop-blur-sm rounded-lg">
                <div class="text-xs opacity-80 mb-1">Mô tả</div>
                <p class="font-medium">{{ dish.description || '---' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.05);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: rgba(99, 102, 241, 0.3);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(99, 102, 241, 0.5);
}
</style>