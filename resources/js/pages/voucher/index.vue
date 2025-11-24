<script setup lang="ts">
import AutoComplete from "primevue/autocomplete";
import Button from "primevue/button";
import Card from "primevue/card";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import Paginator from "primevue/paginator";
import Tag from 'primevue/tag'
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "primevue/usetoast";
import { getVouchers } from "../../../services/voucherService"

const router = useRouter();
const toast = useToast()

const currentPage = ref(1)
const pageSize = ref(20)
const totalVouchers = ref(0)
const loading = ref(false);
const listVoucher = ref([])
const searchQuery = ref('')
const filteredVouchers = ref([])
const currentSearchTerm = ref()

onMounted( async () => {
  loadVoucher()
})

const statusOptions = [
  { label: 'Tất cả', value: -1 },
  { label: 'Hoạt động', value: 1 },
  { label: 'Tạm dừng', value: 0 }
]

const onPageChange = (event:any) => {
  currentPage.value = event.page + 1
  pageSize.value = event.rows

  loadVoucher()
}

const onSearch = () => {
  currentPage.value = 1
  loadVoucher()
}

const getStatusSeverity = (status: number) => status === 1 ? 'success' : 'danger'

const loadVoucher = async () => {
  loading.value = true
  try {
    const resp  = await getVouchers(
      currentPage.value, 
      pageSize.value, 
      searchQuery.value, 
    )
    listVoucher.value = resp.data.data.data
    totalVouchers.value = resp.data.data.total
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Cập nhật danh sách thất bại",
      life: 3000,
    })
  } finally {
    loading.value = false
  }
}

const searchVoucher = async (event: any) => {
  const query = event.query.trim()
  currentSearchTerm.value = query

  if (query.length < 2) {
    filteredVouchers.value = []
    return
  }
  try {
    const resp = await getVouchers(1, 10, query)
    filteredVouchers.value = resp.data.data.data
  } catch (err) {
    filteredVouchers.value = []
  }
}

const onVoucherSelect = (event: any) => {
  const selected = event.value
  searchQuery.value = selected
  listVoucher.value = [selected]
  totalVouchers.value = 1
  currentPage.value = 1
}

const clearSearch = () => {
  searchQuery.value = ""
  currentSearchTerm.value = ""
  filteredVouchers.value = []
  currentPage.value = 1
  loadVoucher()
}

const editVoucher = (event: any) => {
  const voucherID = event.data.id
  router.push({ name: "VoucherDetail", params: { id: voucherID } })
}
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900 p-6">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section with Enhanced Design -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 mb-6">
        <div class="flex justify-between items-center">
          <div class="space-y-2">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                <i class="pi pi-ticket text-white text-xl"></i>
              </div>
              <div>
                <h1 class="font-bold text-3xl bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent">
                  Quản lý Voucher
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  Quản lý và theo dõi tất cả voucher của hệ thống
                </p>
              </div>
            </div>
          </div>
          <Button
            label="Tạo voucher"
            icon="pi pi-plus"
            @click="router.push('voucher/create')"
            class="p-button-rounded !bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 dark:text-white"
          />
        </div>

        <!-- Search Bar with Modern Design -->
        <div class="mt-6">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
              <i class="pi pi-search text-gray-400"></i>
            </div>
            <AutoComplete
              v-model="searchQuery"
              :suggestions="filteredVouchers"
              @complete="searchVoucher"
              @clear="clearSearch"
              @keyup.enter="onSearch"
              @item-select="onVoucherSelect"
              class="w-full"
              optionLabel="code"
              display="chip"
              input-class="w-full !pl-12 !pr-4 !py-3 !rounded-xl !border-2 !border-gray-200 dark:!border-gray-600 focus:!border-indigo-500 dark:focus:!border-indigo-400 !shadow-sm"
              placeholder="Tìm kiếm theo mã voucher..."
            >
              <template #option="{ option }">
                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ option.code }}
                  </div>
                  <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                    {{ option.name }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400 truncate mt-1">
                    {{ option.description }}
                  </div>
                </div>
              </template>
            </AutoComplete>
          </div>
        </div>
      </div>

      <!-- Data Table Card with Enhanced Design -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                <i class="pi pi-list text-indigo-600 dark:text-indigo-400"></i>
              </div>
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                  Danh sách Voucher
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ totalVouchers || 0 }} voucher trong hệ thống
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="overflow-x-auto px-4 py-2">
          <DataTable
            :value="listVoucher"
            :loading="loading"
            data-key="code"
            class="custom-datatable"
            row-hover
            @row-click="editVoucher"
            selection-mode="single"
          >
            <Column
              field="id"
              header="ID"
              style="min-width: 140px;"
            >
              <template #body="{ data }">
                <div class="flex items-center gap-2 py-2">
                  <div class="px-4 py-2.5 rounded-lg bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 border-2 border-blue-200 dark:border-blue-700 shadow-sm">
                    <span class="text-sm font-mono font-bold text-blue-700 dark:text-blue-300">
                      {{ data.id }}
                    </span>
                  </div>
                </div>
              </template>
            </Column>

            <Column
              field="code"
              header="Mã Voucher"
            >
              <template #body="{ data }">
                <div class="flex items-center gap-2 py-2">
                  <div class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 border-2 border-indigo-300 dark:border-indigo-700 shadow-sm">
                    <span class="font-mono font-bold text-indigo-700 dark:text-indigo-300 text-sm tracking-wide">
                      {{ data.code }}
                    </span>
                  </div>
                </div>
              </template>
            </Column>

            <Column
              field="name"
              header="Tên Voucher"
              style="min-width: 300px;"
            >
              <template #body="{ data }">
                <div class="flex items-center gap-4 py-2">
                  <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-400 to-purple-500 flex items-center justify-center shadow-md flex-shrink-0">
                    <i class="pi pi-gift text-white text-base"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="font-semibold text-gray-900 dark:text-white text-base">
                      {{ data.name }}
                    </div>
                  </div>
                </div>
              </template>
            </Column>

            <Column 
              field="conditions" 
              header="Điều kiện" 
              style="min-width: 180px;"
            >
              <template #body="{ data }">
                <div class="py-2">
                  <div v-if="data.conditions?.min_tables" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                      <i class="pi pi-table text-blue-600 dark:text-blue-400 text-sm"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                      {{ data.conditions.min_tables }} bàn trở lên
                    </span>
                  </div>
                  <span v-else class="text-sm text-gray-400 dark:text-gray-500">-</span>
                </div>
              </template>
            </Column>

            <Column
              field="description"
              header="Mô tả"
              style="min-width: 280px;"
            >
              <template #body="{ data }">
                <div class="py-2">
                  <span class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed">
                    {{ data.description || '-' }}
                  </span>
                </div>
              </template>
            </Column>

            <Column header="Trạng thái" style="min-width: 150px;">
              <template #body="{ data }">
                <div class="py-2">
                  <Tag
                    :value="data.is_active === 1 ? 'Hoạt động' : 'Tạm dừng'"
                    :severity="getStatusSeverity(data.is_active)"
                    class="!px-4 !py-2 !rounded-lg !font-medium"
                  />
                </div>
              </template>
            </Column>
          </DataTable>
        </div>

        <!-- Pagination Section -->
        <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
          <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                <i class="pi pi-info-circle text-indigo-600 dark:text-indigo-400 text-sm"></i>
              </div>
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                Hiển thị 
                <span class="text-indigo-600 dark:text-indigo-400 font-bold">
                  {{ ((currentPage - 1) * pageSize) + 1 }}
                </span> 
                đến 
                <span class="text-indigo-600 dark:text-indigo-400 font-bold">
                  {{ Math.min(currentPage * pageSize, totalVouchers || 0) }}
                </span> 
                trong tổng số 
                <span class="text-indigo-600 dark:text-indigo-400 font-bold">
                  {{ totalVouchers || 0 }}
                </span> 
                voucher
              </span>
            </div>
            <Paginator
              :rows="pageSize"
              :total-records="totalVouchers || 0"
              :rows-per-page-options="[10, 20, 30, 50, 100]"
              @page="onPageChange"
              class="custom-paginator"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
