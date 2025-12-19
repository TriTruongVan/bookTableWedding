<script setup>
import ListOrders from '@/components/order/ListOrders.vue';
import AutoComplete from 'primevue/autocomplete';
import Button from 'primevue/button';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter()

</script>
<template>
  <div
    class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900 p-6"
  >
    <div class="max-w-7xl mx-auto">
      <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 mb-6"
      >
        <div class="flex justify-between items-center">
          <div class="space-y-2">
            <div class="flex items-center gap-3">
              <div
                class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg"
              >
                <i class="pi pi-warehouse text-white text-xl"></i>
              </div>
              <div>
                <h1
                  class="font-bold text-3xl bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent"
                >
                  Đơn đặt hàng
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  Quản lý tất cả đơn đặt hàng
                </p>
              </div>
            </div>
          </div>
          <Button
            label="Tạo đơn"
            icon="pi pi-plus"
            @click="router.push('orders/create')"
            class="p-button-rounded !bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 dark:text-white"
          />
        </div>

        <div class="mt-6">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
              <i class="pi pi-search text-gray-400"></i>
            </div>
            <AutoComplete
              :suggestions="filteredCustomers"
              :option-label="getOptionLabel"
              @complete="searchCustomers"
              @clear="loadCustomers"
              @item-select="onCustomerSelect"
              class="w-full"
              input-class="w-full !pl-12 !pr-4 !py-3 !rounded-xl !border-2 !border-gray-200 dark:!border-gray-600 focus:!border-indigo-500 dark:focus:!border-indigo-400 !shadow-sm"
              placeholder="Tìm kiếm mã đơn, dịch vụ hoặc khách hàng..."
            >
              <template #option="{ option }">
                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ option.name }}
                  </div>
                  <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                    {{ option.tel }}
                  </div>
                </div>
              </template>
            </AutoComplete>
          </div>
        </div>
      </div>
    </div>
    <ListOrders/>
  </div>
</template>
