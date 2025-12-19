<script setup>
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import Paginator from "primevue/paginator";
import { ref } from "vue";

const loading = ref(false);
</script>
<template>
  <div
    class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden"
  >
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div
            class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center"
          >
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
      >
        <Column field="id" header="Mã đơn hàng" style="min-width: 140px">
          <template #body>
            <div class="flex items-center gap-2 py-2">
              <div
                class="px-4 py-2.5 rounded-lg bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 border-2 border-blue-200 dark:border-blue-700 shadow-sm"
              >
                <span
                  class="text-sm font-mono font-bold text-blue-700 dark:text-blue-300"
                >
                  <!-- #{{ data.id }} -->
                  191225-01
                </span>
              </div>
            </div>
          </template>
        </Column>
        <Column field="name" header="Khách hàng" style="min-width: 140px">
          <template #body>
            <div class="flex items-center gap-3 py-2">
              <div
                class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex-shrink-0 overflow-hidden"
              >
                <img
                  src="https://randomuser.me/api/portraits/men/32.jpg"
                  alt="Customer"
                  class="w-full h-full object-cover"
                />
              </div>
              <div>
                <p class="font-medium text-gray-900 dark:text-white">
                  Nguyễn Văn A
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  0123456789
                </p>
              </div>
            </div>
          </template>
        </Column>
        <Column field="status" header="Trạng thái" style="min-width: 140px">
          <template #body>
            <span
              class="px-3 py-1.5 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400"
            >
              Đã thanh toán
            </span>
          </template>
        </Column>

        <Column field="staff" header="Nhân viên" style="min-width: 140px">
          <template #body>
            <div class="flex items-center gap-2">
              <div
                class="w-8 h-8 rounded-full bg-indigo-200 dark:bg-indigo-800 flex items-center justify-center text-xs font-bold text-indigo-700 dark:text-indigo-300"
              >
                LT
              </div>
              <span class="font-medium text-gray-900 dark:text-white"
                >Lê Thị Thu</span
              >
            </div>
          </template>
        </Column>

        <Column field="dishes" header="Món ăn" style="min-width: 140px">
          <template #body>
            <div class="text-sm text-gray-700 dark:text-gray-300">
              <p class="font-medium">Phở bò tái nạm</p>
              <p class="text-gray-500 dark:text-gray-400">+ 2 món khác</p>
            </div>
          </template>
        </Column>

        <Column field="table" header="Số bàn" style="min-width: 140px">
          <template #body>
            <span
              class="px-4 py-2 rounded-lg bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 font-bold"
            >
              Bàn 05
            </span>
          </template>
        </Column>

        <Column field="total" header="Tổng đơn" style="min-width: 140px">
          <template #body>
            <span class="text-lg font-bold text-gray-900 dark:text-white">
              285.000 đ
            </span>
          </template>
        </Column>
      </DataTable>
    </div>
    <div
      class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50"
    >
      <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-2">
          <div
            class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center"
          >
            <i
              class="pi pi-info-circle text-indigo-600 dark:text-indigo-400 text-sm"
            ></i>
          </div>
          <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
            Hiển thị
            <span class="text-indigo-600 dark:text-indigo-400 font-bold">
              {{ (page - 1) * rows + 1 }}
            </span>
            đến
            <span class="text-indigo-600 dark:text-indigo-400 font-bold">
              {{ Math.min(page * rows, total || 0) }}
            </span>
            trong tổng số
            <span class="text-indigo-600 dark:text-indigo-400 font-bold">
              {{ total || 0 }}
            </span>
            đơn hàng
          </span>
        </div>
        <Paginator
          :rows="rows"
          :total-records="total || 0"
          :rows-per-page-options="[10, 20, 30, 50, 100]"
          :first="(page - 1) * rows"
          class="custom-paginator"
        />
      </div>
    </div>
  </div>
</template>