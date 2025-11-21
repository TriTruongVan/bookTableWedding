<script setup lang="ts">
import AutoComplete from 'primevue/autocomplete';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import { useToast } from 'primevue/usetoast';
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { getAllStaff, getStaffByTelOrName } from '../../../services/staffService';
import Paginator from 'primevue/paginator';

const router = useRouter()
const toast = useToast()
const page = ref(1)
const rows = ref(20)
const total = ref(0)
const loading = ref(false)
const meta = ref()
const sortField = ref("created_at")
const sortOrder = ref("desc")
const staff = ref([{name:"", tel:"", address: "", status: ""}])
const currentQuery = ref("")
const filteredStaff = ref([])

onMounted(async() => {
  await loadStaff()
})

const onPageChange = (event:any) => {
  page.value = event.page + 1
  rows.value = event.rows

  loadStaff()
}

const onSort = (event: any) => {
  staff.value = []
  loading.value = true
  if (event.sortOrder === null) {
    sortField.value = "create_at"
    sortOrder.value = "desc"
  } else {
    sortField.value = event.sortField
    sortOrder.value = event.sortOrder === 1 ? "asc" : "desc"
  }
  page.value = 1
  loadStaff()
}

const editStaff = (event: any) => {
  const staffId = event.data.id
  router.push({ name: "StaffDetail", params: { id: staffId } })
}

const loadStaff = async ( event?: any) =>{
  loading.value = true 
  try {
    const pageNum = event
      ? Math.floor(event.first / event.rows) + 1
      : page.value
    const pageSize = event ? event.rows : rows.value
    router.replace({
      query: {
        page: pageNum,
        size: pageSize,
        sort_by: sortField.value,
        sort_dir: sortOrder.value,
      },
    })

    const resp = await getAllStaff(pageNum, pageSize, sortField.value, sortOrder.value)
    staff.value = resp.data.data.data
    console.log("hhehe",staff.value);
    

    meta.value = resp.data.data
    page.value = meta.value.currentPage
    rows.value = meta.value.perPage
    total.value = meta.value.total
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

const searchStaff = async (event:any) => {
  const query = event.query
  currentQuery.value = query
  if (query.length >= 2) {
    const searchResults = ( await getStaffByTelOrName(query)).data.data.items
    filteredStaff.value = searchResults.slice(0, 10)
  }
}

const getStatusText = (status: number | string) => {
  return status == 1 ? 'Phục vụ' : status == 0 ? 'Bếp' : '-';
};

const isPhoneNumber = (query: string) => {
  const digitCount = (query.match(/\d/g) || []).length
  return digitCount >= query.length * 0.7
}

const getOptionLabel = (option: any) => {
  return isPhoneNumber(currentQuery.value) ? option.tel : option.name
}

const onStaffSelect = (selectedStaff: any) => {
  const filterStaff = staff.value.filter((cus) => {
    return cus.tel === selectedStaff.value.tel
  })
  staff.value = filterStaff
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
                <i class="pi pi-users text-white text-xl"></i>
              </div>
              <div>
                <h1 class="font-bold text-3xl bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent">
                  Quản lý nhân viên
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  Quản lý và theo dõi thông tin Nhân viên
                </p>
              </div>
            </div>
          </div>
          <Button
            label="Tạo Nhân viên"
            icon="pi pi-plus"
            @click="router.push('staff/create')"
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
              :suggestions="filteredStaff"
              :option-label="getOptionLabel"
              @complete="searchStaff"
              @clear="loadStaff"
              @item-select="onStaffSelect"
              class="w-full"
              input-class="w-full !pl-12 !pr-4 !py-3 !rounded-xl !border-2 !border-gray-200 dark:!border-gray-600 focus:!border-indigo-500 dark:focus:!border-indigo-400 !shadow-sm hover:!shadow-md transition-shadow"
              placeholder="Tìm kiếm theo tên hoặc số điện thoại..."
            >
              <template #option="{ option }">
                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ option.name }}
                  </div>
                  <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                    {{ option.tel }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400 truncate mt-1">
                    {{ option.address }}
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
                  Danh sách Nhân viên
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ total || 0 }} Nhân viên trong hệ thống
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="overflow-x-auto px-4 py-2">
          <DataTable
            :value="staff"
            :loading="loading"
            data-key="id"
            class="custom-datatable"
            row-hover
            selection-mode="single"
            :sort-field="sortField"
            :sort-order="sortOrder === 'asc' ? 1 : sortOrder === 'desc' ? -1 : 0"
            :removable-sort="true"
            @sort="onSort"
            @row-click="editStaff"
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
                      #{{ data.id }}
                    </span>
                  </div>
                </div>
              </template>
            </Column>

            <Column
              field="name"
              header="Tên Nhân viên"
              :sortable="true"
              style="min-width: 200px;"
            >
              <template #body="{ data }">
                <div class="flex items-center gap-4 py-2">
                  <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-md flex-shrink-0">
                    <i class="pi pi-user text-white text-base"></i>
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
              field="address"
              header="Địa chỉ"
              :sortable="true"
              style="min-width: 280px;"
            >
              <template #body="{ data }">
                <div class="py-2 flex items-center gap-2">
                  <div class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="pi pi-map-marker text-orange-600 dark:text-orange-400 text-xs"></i>
                  </div>
                  <span class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed">
                    {{ data.address || '-' }}
                  </span>
                </div>
              </template>
            </Column>

            <Column
              field="tel"
              header="Điện thoại"
              :sortable="true"
              style="min-width: 200px;"
            >
              <template #body="{ data }">
                <div class="py-2">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                      <i class="pi pi-phone text-green-600 dark:text-green-400 text-sm"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 font-mono">
                      {{ data.tel }}
                    </span>
                  </div>
                </div>
              </template>
            </Column>
            <Column
              field="status"
              header="Loại nhân viên"
              :sortable="true"
              style="min-width: 200px;"
            >
              <template #body="{ data }">
                <div class="py-2">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                      <i class="pi pi-user text-green-600 dark:text-green-400 text-sm"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 font-mono">
                      {{getStatusText(data.status)}}
                    </span>
                  </div>
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
                Nhân viên
              </span>
            </div>
            <Paginator
              :rows="rows"
              :total-records="total || 0"
              :rows-per-page-options="[10, 20, 30, 50, 100]"
              :first="(page - 1) * rows"
              @page="onPageChange"
              class="custom-paginator"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>