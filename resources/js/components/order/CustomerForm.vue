<script setup>
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import { computed, ref } from 'vue';
import { getCustomersByTel } from '../../../services/customerService';

const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  wards: {
    type: Array,
    required: true
  }
});

const emit = defineEmits(['update:modelValue']);
const filteredCustomers = ref([]);

const customer = computed({
  get: () => props.modelValue,
  set: (newValue) => emit('update:modelValue', newValue)
});

const searchCustomers = async (event) => {
  const query = event.query.trim();
  if (query.length >= 2) {
    try {
      const response = await getCustomersByTel(query);
      filteredCustomers.value = response.data?.data?.items?.slice(0, 10) || [];
    } catch (error) {
      console.error('Lỗi tìm kiếm khách hàng:', error);
      filteredCustomers.value = [];
    }
  } else {
    filteredCustomers.value = [];
  }
};

const onCustomerSelect = (selectedCustomer) => {
  customer.value = {
    name: selectedCustomer.value.name,
    tel: selectedCustomer.value.tel,
    street: selectedCustomer.value.street,
    ward: selectedCustomer.value.ward,
  }
}
</script>

<template>
  <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl">
    <!-- Header -->
    <div class="mb-6 text-center">
      <h1 class="text-2xl font-bold text-indigo-900 dark:text-indigo-300 mb-1">
        Thông tin khách hàng
      </h1>
      <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">THANH AN HỘI</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
      <div class="p-6">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Thông tin khách hàng</h2>
        </div>

        <div class="space-y-5">
          <!-- Row 1: Tên & Số điện thoại -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Tên khách hàng -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tên khách hàng <span class="text-red-500">*</span>
              </label>
              <InputText
                v-model="customer.name"
                required
                placeholder="Nhập họ và tên"
                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all dark:bg-gray-700 dark:text-gray-100"
              />
            </div>

            <!-- Số điện thoại - Đã chỉnh giống hình -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Số điện thoại <span class="text-red-500">*</span>
              </label>
              <AutoComplete
                v-model="customer.tel"
                option-label="tel"
                placeholder="Nhập số điện thoại"
                class="w-full"
                :suggestions="filteredCustomers"
                @complete="searchCustomers"
                @item-select="onCustomerSelect"
                :dropdown="false"
                input-class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all dark:bg-gray-700 dark:text-gray-100"
              >
                <template #option="{ option }">
                  <div class="p-2">
                    <div class="font-medium text-gray-900 dark:text-white">
                      {{ option.name }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">
                      {{ option.tel }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                      {{ option.address || '' }}
                    </div>
                  </div>
                </template>
              </AutoComplete>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 italic">
                Nhập ít nhất 2 số để tìm kiếm khách hàng có sẵn
              </p>
            </div>
          </div>

          <!-- Row 2: Địa chỉ & Xã/Phường (giữ nguyên) -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Số nhà, tên đường <span class="text-red-500">*</span>
              </label>
              <InputText
                v-model="customer.street"
                required
                placeholder="Nhập số nhà, tên đường"
                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all dark:bg-gray-700 dark:text-gray-100"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Xã / Phường <span class="text-red-500">*</span>
              </label>
              <Dropdown
                v-model="customer.ward"
                required
                placeholder="Chọn xã / phường"
                :options="wards"
                option-label="label"
                option-value="id"
                class="w-full"
                :pt="{
                  root: { class: 'w-full border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all dark:bg-gray-700' }
                }"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>