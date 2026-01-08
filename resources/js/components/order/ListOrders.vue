<script setup lang="ts">
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import Paginator from "primevue/paginator";
import { ref } from "vue";

const loading = ref(false);
const currentPage = ref(1)
const pageSize = ref(20)
const sortField = ref(null);
const sortOrder = ref(null);

const props = defineProps({
  modelValue: {
    type: Object,
    default: () => [],
  },
  listOrder: {
    type: Array,
    required: true
  },
  total:{
    type: Number,
  }
})

const formatDate = (dateString: any) => {
  return new Date(dateString).toLocaleDateString("vi-VN");
};

const formatCurrency = (value: any) => {
  return Number(value || 0).toLocaleString("vi-VN");
};

const getStatusColor = (status: string) => {
  switch (status) {
    case 'san_sang':
      return 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800';
    case 'ket_thuc':
      return 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800';
    default:
      return 'bg-gray-50 text-gray-600 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700';
  }
};

const formatSession = (session: string) => {
  switch (session) {
    case 'trua':
      return 'Trưa';
    case 'chieu':
      return 'Chiều';
    default:
      return session;
  }
};

const getStatusText = (status: string) => {
  switch (status) {
    case 'san_sang':
      return 'Sẵn sàng';
    case 'ket_thuc':
      return 'Kết thúc';
    default:
      return status;
  }
};
</script>

<template>
  <div class="w-full">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
      <!-- Header -->
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
              <i class="pi pi-list text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <div>
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Danh sách đơn hàng
              </h2>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ total || 0 }} đơn hàng trong hệ thống
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto px-4 py-2">
        <DataTable
          :loading="loading"
          data-key="id"
          class="custom-datatable"
          row-hover
          selection-mode="single"
          :sort-field="sortField"
          :sort-order="sortOrder === 'asc' ? 1 : sortOrder === 'desc' ? -1 : 0"
          :removable-sort="true"
          :value="listOrder"
        >
          <!-- Mã đơn hàng -->
          <Column field="id" header="MÃ ĐơN" style="min-width: 150px">
            <template #body="{data}">
              <div class="flex items-center gap-2 py-2">
                <div class="px-4 py-2.5 rounded-lg bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 border-2 border-blue-200 dark:border-blue-700 shadow-sm">
                  <span class="text-sm font-mono font-bold text-blue-700 dark:text-blue-300">
                    #{{ data.id }}
                  </span>
                </div>
              </div>
            </template>
          </Column>

          <!-- Khách hàng -->
          <Column field="name" header="KHÁCH HÀNG" style="min-width: 300px">
            <template #body="{data}">
              <div class="flex items-center gap-4 py-2">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-md flex-shrink-0">
                  <i class="pi pi-user text-white text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="font-semibold text-gray-900 dark:text-white text-base mb-1">
                    {{ data.customer.name }}
                  </div>
                  <div class="text-sm text-gray-600 dark:text-gray-400 mb-0.5">
                    {{ data.customer.tel }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-500 line-clamp-1">
                    {{ data.customer.address }}
                  </div>
                </div>
              </div>
            </template>
          </Column>

          <!-- Ngày tiệc -->
          <Column field="party_date" header="Ngày tiệc" style="min-width: 140px">
            <template #body="{data}">
              <div class="py-1">
                <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                  {{ data.lunar_date }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-0.5">
                  {{ formatDate(data.event_date) }}
                </p>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700 dark:bg-purple-900/20 dark:text-purple-400">
                  Buổi {{ formatSession(data.session) }}
                </span>
              </div>
            </template>
          </Column>

          <!-- Địa điểm -->
          <Column field="address" header="ĐỊA ĐIỂM" style="min-width: 150px">
            <template #body="{data}">
              <div class="py-2 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0">
                  <i class="pi pi-map-marker text-orange-600 dark:text-orange-400 text-xs"></i>
                </div>
                <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                  {{ data.note }}
                </span>
              </div>
            </template>
          </Column>

          <!-- Món ăn -->
          <Column field="dishes" header="MÓN ĂN" style="min-width: 220px">
            <template #body="{data}">
              <div class="space-y-1.5 py-2">
                <div v-for="dish in data.dishes" :key="dish.id" class="flex items-start gap-2">
                  <span class="text-indigo-400 dark:text-indigo-500 text-sm mt-0.5 font-bold">•</span>
                  <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    {{ dish.name }}
                  </span>
                </div>
              </div>
            </template>
          </Column>

          <!-- Số bàn -->
          <Column field="table" header="SỐ BÀN" style="min-width: 120px">
            <template #body="{data}">
              <div class="py-2">
                <div class="inline-flex items-baseline gap-1 px-3 py-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 shadow-sm">
                  <span class="text-lg font-bold text-amber-700 dark:text-amber-400">
                    {{ data.table_count }}
                  </span>
                  <span class="text-sm text-amber-600 dark:text-amber-500">
                    bàn
                  </span>
                </div>
              </div>
            </template>
          </Column>
  
          <!-- Nhân viên -->
          <Column field="staff" header="Nhân viên" style="min-width: 150px">
            <template #body="{data}">
              <div class="space-y-1.5 py-1">
                <div v-for="staff in data.staffs" :key="staff.id" class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-semibold text-indigo-700 dark:text-indigo-400">
                      {{ staff.name.charAt(0) }}
                    </span>
                  </div>
                  <span class="text-sm text-gray-700 dark:text-gray-300 truncate">
                    {{ staff.name }}
                  </span>
                </div>
              </div>
            </template>
          </Column>

          <!-- Tổng tiền -->
          <Column field="total" header="TỔNG TIỀN" style="min-width: 140px">
            <template #body="{data}">
              <div class="py-2">
                <div class="text-right">
                  <p class="text-base font-bold text-emerald-600 dark:text-emerald-400">
                    {{ formatCurrency(data.total_amount) }}
                  </p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    triệu đồng
                  </p>
                </div>
              </div>
            </template>
          </Column>

          <!-- Trạng thái -->
          <Column field="status" header="Trạng thái" style="min-width: 110px">
            <template #body="{data}">
              <span
                :class="getStatusColor(data.status)"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-full border"
              >
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                {{ getStatusText(data.status) }}
              </span>
            </template>
          </Column>
        </DataTable>
      </div>

      <!-- Footer with Pagination -->
      <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
          <div class="text-sm text-gray-600 dark:text-gray-400">
            Hiển thị 
            <span class="font-semibold text-gray-900 dark:text-white">
              {{ ((currentPage - 1) * pageSize) + 1 }}
            </span> 
            đến 
            <span class="font-semibold text-gray-900 dark:text-white">
              {{ Math.min(currentPage * pageSize, total || 0) }}
            </span> 
            trong tổng số 
            <span class="font-semibold text-gray-900 dark:text-white">
              {{ total || 0 }}
            </span> 
            đơn hàng
          </div>
          <Paginator
            :rows="pageSize"
            :total-records="total || 0"
            :rows-per-page-options="[10, 20, 30, 50, 100]"
            @page="onPageChange"
            class="custom-paginator"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Custom DataTable Styling */
:deep(.custom-datatable) {
  font-size: 0.875rem;
}

:deep(.custom-datatable .p-datatable-thead > tr > th) {
  background-color: #f9fafb;
  color: #374151;
  font-weight: 600;
  font-size: 0.6875rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  padding: 1rem 1rem;
  border-bottom: 2px solid #e5e7eb;
}

:deep(.dark .custom-datatable .p-datatable-thead > tr > th) {
  background-color: #1f2937;
  color: #d1d5db;
  border-bottom-color: #374151;
}

:deep(.custom-datatable .p-datatable-tbody > tr > td) {
  padding: 1.25rem 1rem;
  border-bottom: 1px solid #f3f4f6;
  vertical-align: middle;
}

:deep(.dark .custom-datatable .p-datatable-tbody > tr > td) {
  border-bottom-color: #1f2937;
}

:deep(.custom-datatable .p-datatable-tbody > tr:hover) {
  background-color: #f9fafb;
}

:deep(.dark .custom-datatable .p-datatable-tbody > tr:hover) {
  background-color: #1f2937;
}

:deep(.custom-datatable .p-datatable-tbody > tr:last-child > td) {
  border-bottom: none;
}
</style>