<script setup lang="ts">
import Card from 'primevue/card';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getCustomerById, updateCustomer } from "../../../services/customerService"; 
import { getAllWards } from '../../../services/userService'

const router = useRouter()
const route = useRoute();
const loading = ref(false)
const toast = useToast()
const wardsList = ref([])
const customer = ref({
  name: '',
  tel: '',
  street: '',
  ward: null,
  role: null,
})

onMounted(async () => {
  await loadCustomer()
  await loadWards()
})

const loadWards = async () => {
  loading.value = true
  try {
    wardsList.value = (await getAllWards()).data.data.wards
    if (!wardsList.value) {
      toast.add({
        severity: 'error',
        summary: t('Error'),
        detail: t('WardsNotFound'),
        life: 3000
      })
      return
    }
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Cập nhật địa chỉ thất bại',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}
const roleList = ref([
  { id: 0, label: 'Cá nhân'},
  { id: 1, label: 'Doanh nghiệp'}
])

const handleSubmit = async () => {
  loading.value = true
  try {
    await updateCustomer(route.params.id, customer.value)
    toast.add({
      severity: 'success',
      summary: 'Success',
      detail: 'Cập nhật khách hàng thành công',
      life: 3000
    })

    router.push('/customer')
  } catch (error: any) {
    console.log(error);
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Cập nhật khách hàng thất bại',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}

const loadCustomer = async () => {
  loading.value = true
  try {
    const response = await getCustomerById(route.params.id)
    customer.value = response.data.data || customer.value
    if (!customer.value) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'CustomerNotFound',
        life: 3000
      })
      router.push('/customer')
    }
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'FailedToLoadCustomerDetails',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header: Back + Title -->
    <div class="flex items-center mb-6 px-6 pt-6">
      <button
        class="mr-4 p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
        aria-label="Quay lại"
        @click="router.push('/customer')"
      >
        <i class="pi pi-arrow-left text-gray-600 dark:text-gray-300 text-lg"></i>
      </button>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
        Cập nhật thông tin khách hàng
      </h1>
    </div>

    <!-- Main Grid -->
    <div class="px-6 pb-6">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Card -->
        <div class="lg:col-span-2">
          <Card class="shadow-md border-0  border-spacing-1 dark:border">
            <template #content>
              <form
                class="space-y-8"
                @submit.prevent="handleSubmit"
              >
                <!-- Thông tin khách hàng -->
                <div class="bg-gray-50 dark:bg-gray-800/50 p-6 rounded-xl">
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                    Thông tin khách hàng
                  </h3>

                  <!-- Họ và tên -->
                  <div class="grid grid-cols-1 gap-6">
                    <div>
                      <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Họ và tên <span class="text-red-500">*</span>
                      </label>
                      <InputText
                        id="name"
                        v-model="customer.name"
                        type="text"
                        placeholder="Nhập họ và tên"
                        class="w-full"
                      />
                    </div>
                  </div>

                  <!-- Grid 2 cột -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Đường -->
                    <div>
                      <label for="street" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Đường <span class="text-red-500">*</span>
                      </label>
                      <InputText
                        id="street"
                        v-model="customer.street"
                        type="text"
                        placeholder="Nhập tên đường"
                        class="w-full"
                      />
                    </div>

                    <!-- Xã / Phường -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Xã / Phường <span class="text-red-500">*</span>
                      </label>
                      <Dropdown
                        v-model="customer.ward"
                        :options="wardsList"
                        placeholder="Chọn xã / phường"
                        option-label="label"
                        option-value="id"
                        class="w-full"
                      />
                    </div>

                    <!-- Hệ khách hàng -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Hệ khách hàng <span class="text-red-500">*</span>
                      </label>
                      <Dropdown
                        v-model="customer.role"
                        :options="roleList"
                        placeholder="Chọn hệ khách hàng"
                        class="w-full"
                        option-label="label"
                        option-value="id"
                        :required="true"
                      />
                    </div>
                    <!-- Số điện thoại -->
                    <div>
                      <label for="tel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Số điện thoại <span class="text-red-500">*</span>
                      </label>
                      <InputText
                        v-model="customer.tel"
                        id="tel"
                        type="text"
                        placeholder="Nhập số điện thoại"
                        class="w-full"
                      />
                    </div>
                  </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                  <button
                    type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-lg hover:opacity-90 transition-opacity"
                  >
                    Cập nhật khách hàng
                  </button>
                </div>
              </form>
            </template>
          </Card>
        </div>

        <div class="lg:col-span-1">
          <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5">
            <h4 class="font-medium text-blue-900 dark:text-blue-300 mb-3">
              <i class="pi pi-info-circle mr-2"></i>
              Hướng dẫn
            </h4>
            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                <li>• Kiểm tra lại địa chỉ trước khi cập nhật</li>
                <li>• Nhấn “Cập nhật khách hàng” để lưu thông tin</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>