<script setup lang="ts">
import Button from "primevue/button";
import Card from "primevue/card";
import AutoComplete from "primevue/autocomplete";
import { useRouter } from "vue-router";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Paginator from 'primevue/paginator';
import { onMounted, ref } from "vue";
import { getAllCustomers, getCustomersByTelOrName } from "../../../services/customerService";
import { useToast } from "primevue/usetoast";

const meta = ref()
const toast = useToast()
const router = useRouter();
const page = ref(1)
const rows = ref(20)
const total = ref(0)
const loading = ref(false)
const sortField = ref("created_at")
const sortOrder = ref("desc")
const customers = ref([{name:"", tel:"", ward: 0, street: ""}])
const filteredCustomers = ref([])
const currentQuery = ref("")

onMounted(async () => {
  await loadCustomers()
})

const onPageChange = (event:any) => {
  page.value = event.page + 1
  rows.value = event.rows

  loadCustomers()
}

const getRoleLabel = (role: number) => {
  const roleMaps: Record<number, string> = {
    0: 'Cá nhân',
    1: 'Doanh nghiệp',
  }

  return roleMaps[role] || 'unknown'
}

const loadCustomers = async (event?: any) => {
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

    const resp = await getAllCustomers(
      pageNum,
      pageSize,
      sortField.value,
      sortOrder.value,
    )
    customers.value = resp.data.data.data
    console.log(customers.value);
    
    meta.value = resp.data.data
    page.value = meta.value.currentPage
    rows.value = meta.value.perPage
    total.value = meta.value.total
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Cập nhật danh sách thất bại",
      life: 3000,
    })
  } finally {
    loading.value = false
  }
}
const onSort = (event: any) => {
  customers.value = []
  loading.value = true
  if (event.sortOrder === null) {
    sortField.value = "create_at"
    sortOrder.value = "desc"
  } else {
    sortField.value = event.sortField
    sortOrder.value = event.sortOrder === 1 ? "asc" : "desc"
  }
  page.value = 1
  loadCustomers()
}

const searchCustomers = async  (event: any) => {
  const query = event.query
  currentQuery.value = query
  if (query.length >= 2) {
    const searchResults = ( await getCustomersByTelOrName(query)).data.data.items
    filteredCustomers.value = searchResults.slice(0, 10)
  }
}

const isPhoneNumber = (query: string) => {
  const digitCount = (query.match(/\d/g) || []).length
  return digitCount >= query.length * 0.7
}

const getOptionLabel = (option: any) => {
  return isPhoneNumber(currentQuery.value) ? option.tel : option.name
}

const onCustomerSelect = (selectedCustomer: any) => {
  const filterCustomer = customers.value.filter((cus) => {
    return cus.tel === selectedCustomer.value.tel
  })
  customers.value = filterCustomer
}

const editCustomer = (event: any) => {
  const customerId = event.data.id
  router.push({ name: "CustomerDetail", params: { id: customerId } })
}
</script>
<template>
  <div
    class="bg-white dark:bg-gray-800/90 border border-spacing-1 rounded-lg p-4 overflow-visible"
  >
    <div class="flex justify-between">
      <div class="">
        <h1 class="font-bold text-xl dark:text-white">khách hàng</h1>
        <p class="text-sm dark:text-white">Quản lý thông tin khách hàng</p>
      </div>
      <Button
        label="Tạo khách hàng"
        icon="pi pi-plus"
        @click="router.push('customer/create')"
        class="p-button-rounded !bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 dark:text-white"
      />
    </div>
    <Card class="mt-4 !shadow-lg">
      <template #content>
        <AutoComplete
          :suggestions="filteredCustomers"
          :option-label="getOptionLabel"
          @complete="searchCustomers"
          @clear="loadCustomers"
          class="w-full mb-4"
          input-class="w-full"
          placeholder=" 🔍 Tìm kiếm theo tên, Email hoặc số điện thoại..."
          @item-select="onCustomerSelect"
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
                {{ option.address }}
              </div>
            </div>
          </template>
        </AutoComplete>
        <DataTable
          :value="customers"
          :loading="loading"
          data-key="id"
          class="p-datatable-sm"
          row-hover
          selection-mode="single"
          :sort-field="sortField"
          :sort-order="sortOrder === 'asc' ? 1 : sortOrder === 'desc' ? -1 : 0"
          :removable-sort="true"
          @sort="onSort"
          @row-click="editCustomer"
        >
          <Column
            field="name"
            :header="'Tên'"
            :sortable="true"
            style="min-width: 200px;"
          >
          <template #body="{ data }">
              <span class="text-sm text-gray-600 dark:text-gray-300">
                {{ data.name }}
              </span>
            </template>
          </Column>
          <Column
            field="ward"
            :header="'Địa chỉ'"
            :sortable="true"
            style="min-width: 200px;"
          >
          <template #body="{ data }">
              <span class="text-sm text-gray-600 dark:text-gray-300">
                {{ data.address }}
              </span>
            </template>
          </Column>
          <Column
            field="tel"
            :header="'Điện thoại'"
            :sortable="true"
            style="min-width: 200px;"
          >
          <template #body="{ data }">
              <span class="text-sm text-gray-600 dark:text-gray-300">
                {{ data.tel }}
              </span>
            </template>
          </Column>
          <Column
            field="role"
            :header="'Hệ khách hàng'"
            :sortable="true"
            style="min-width: 200px;"
          >
          <template #body="{ data }">
              <span class="text-sm text-gray-600 dark:text-gray-300">
                {{getRoleLabel(data.role) }}
              </span>
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>
    <!-- Pagination -->
    <div class="mt-4 flex justify-between items-center">
      <div class="text-sm text-gray-600 dark:text-gray-300">
        Hiển thị {{ (page - 1) * rows + 1 }} đến
        {{ Math.min(page * rows, total || 0) }} trong tổng số {{ total || 0 }}
        khách hàng
      </div>
      <Paginator
        :rows="rows"
        :total-records="total || 0"
        :rows-per-page-options="[10, 20, 30, 50, 100]"
        :first="(page - 1) * rows"
        @page="onPageChange"
      />
    </div>
  </div>
</template>