<script setup lang="ts">
import { onMounted, ref } from "vue";
import { getOrders } from "../../../services/orderService";
import { useToast } from "primevue/usetoast";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Paginator from "primevue/paginator";
import { getDashBoardSummary } from "../../../services/dashboardService";
import { useRouter } from "vue-router";

const loading = ref(false);
const toast = useToast();
const listOrder = ref([]);
const total = ref(0);
const pageSize = ref(20);
const currentPage = ref(1);
const totalCustomer = ref<number>(0);
const totalRevenue = ref<number>(0);
const totalTable = ref<number>(0);
const router = useRouter();

onMounted(async () => {
  await Promise.all([loadDashBoardSummary(), loadOrder()]);
});

const loadOrder = async () => {
  loading.value = true;
  try {
    const respOrder = await getOrders(currentPage.value, pageSize.value);
    listOrder.value = respOrder.data.data.data;
    total.value = respOrder.data.data.total;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Cập nhật danh sách thất bại",
      life: 3000,
    });
  } finally {
    loading.value = false;
  }
};

const loadDashBoardSummary = async () => {
  loading.value = true;
  try {
    const reps = await getDashBoardSummary();
    const data = reps.data.data;
    totalTable.value = data.total_table;
    totalCustomer.value = data.total_customer;
    totalRevenue.value = data.total_revenue;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Cập nhật Dashboard thất bại",
      life: 3000,
    });
  } finally {
    loading.value = false;
  }
};

const formatDate = (dateString: any) => {
  return new Date(dateString).toLocaleDateString("vi-VN");
};

const formatSession = (session: string) => {
  switch (session) {
    case "trua":
      return "Trưa";
    case "chieu":
      return "Chiều";
    default:
      return session;
  }
};

const formatCurrency = (value: any) => {
  return Number(value || 0).toLocaleString("vi-VN");
};

const getStatusText = (status: string) => {
  switch (status) {
    case "san_sang":
      return "Sẵn sàng";
    case "ket_thuc":
      return "Kết thúc";
    default:
      return status;
  }
};

const getStatusColor = (status: string) => {
  switch (status) {
    case "san_sang":
      return "bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800";
    case "ket_thuc":
      return "bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800";
    default:
      return "bg-gray-50 text-gray-600 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700";
  }
};

const editOrder = (event: any) => {
  const orderID = event.data.id;
  router.push({ name: "OrderDetail", params: { id: orderID } });
};
</script>
<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 mb-8"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div
              class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center"
            >
              <svg
                class="w-7 h-7 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </div>
            <div>
              <h1
                class="text-3xl font-bold text-purple-600 dark:text-purple-400"
              >
                Bảng điều khiển
              </h1>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Tổng quan về hệ thống quản lý dịch vụ của bạn
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Tổng đặt bàn -->
        <div
          class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-start justify-between mb-4">
            <div>
              <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">
                Đơn hàng
              </p>
              <h2 class="text-4xl font-bold text-gray-900 dark:text-white">
                {{ total }}
              </h2>
            </div>
            <div
              class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-xl flex items-center justify-center"
            >
              <svg
                class="w-6 h-6 text-purple-600 dark:text-purple-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </div>
          </div>
          <p class="text-green-600 dark:text-green-400 text-sm">
            ↑ +12% so với tháng trước
          </p>
        </div>

        <!-- Khách hàng -->
        <div
          class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-start justify-between mb-4">
            <div>
              <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">
                Khách hàng
              </p>
              <h2 class="text-4xl font-bold text-gray-900 dark:text-white">
                {{ totalCustomer }}
              </h2>
            </div>
            <div
              class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center"
            >
              <svg
                class="w-6 h-6 text-blue-600 dark:text-blue-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                />
              </svg>
            </div>
          </div>
          <p class="text-green-600 dark:text-green-400 text-sm">
            ↑ +8% so với tháng trước
          </p>
        </div>

        <!-- Doanh thu -->
        <div
          class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-start justify-between mb-4">
            <div>
              <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">
                Doanh thu
              </p>
              <h2 class="text-4xl font-bold text-gray-900 dark:text-white">
                {{ formatCurrency(totalRevenue) }}
              </h2>
            </div>
            <div
              class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-xl flex items-center justify-center"
            >
              <svg
                class="w-6 h-6 text-green-600 dark:text-green-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
          </div>
          <p class="text-green-600 dark:text-green-400 text-sm">
            ↑ +23% so với tháng trước
          </p>
        </div>

        <!-- Tăng trưởng -->
        <div
          class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-start justify-between mb-4">
            <div>
              <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">
                Tổng số bàn
              </p>
              <h2 class="text-4xl font-bold text-gray-900 dark:text-white">
                {{ totalTable }}
              </h2>
            </div>
            <div
              class="w-12 h-12 bg-yellow-50 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center"
            >
              <svg
                class="w-6 h-6 text-yellow-600 dark:text-yellow-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                />
              </svg>
            </div>
          </div>
          <p class="text-green-600 dark:text-green-400 text-sm">
            ↑ +5% so với tháng trước
          </p>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Đặt bàn gần đây -->
        <div
          class="lg:col-span-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center gap-3 mb-6">
            <div
              class="w-10 h-10 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center"
            >
              <svg
                class="w-5 h-5 text-purple-600 dark:text-purple-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 10h16M4 14h16M4 18h16"
                />
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                Đặt bàn gần đây
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ total || 0 }} đơn hàng trong hệ thống
              </p>
            </div>
          </div>

          <div class="overflow-x-auto px-4 py-2">
            <DataTable
              :loading="loading"
              data-key="id"
              row-hover
              selection-mode="single"
              :value="listOrder"
              @row-click="editOrder"
              :sort-field="sortField"
              :sort-order="
                sortOrder === 'asc' ? 1 : sortOrder === 'desc' ? -1 : 0
              "
            >
              <Column field="name" header="KHÁCH HÀNG" style="min-width: 160px">
                <template #body="{ data }">
                  <div class="flex items-center gap-4">
                    <div class="flex-1 min-w-0">
                      <div
                        class="font-semibold text-gray-900 dark:text-white text-base mb-1"
                      >
                        {{ data.customer.name }}
                      </div>
                    </div>
                  </div>
                </template>
              </Column>
              <!-- Ngày tiệc -->
              <Column
                field="party_date"
                header="Ngày tiệc"
                style="min-width: 10px"
              >
                <template #body="{ data }">
                  <div class="py-1">
                    <p
                      class="text-sm font-medium text-gray-900 dark:text-white mb-1"
                    >
                      Âm lịch: {{ data.lunar_date }}
                    </p>
                  </div>
                </template>
              </Column>
              <!-- Số bàn -->
              <Column field="table" header="SỐ BÀN" style="min-width: 10px">
                <template #body="{ data }">
                  <div class="py-2">
                    <div
                      class="inline-flex items-baseline gap-1 px-3 py-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 shadow-sm"
                    >
                      <span
                        class="text-lg font-bold text-amber-700 dark:text-amber-400"
                      >
                        {{ data.table_count }}
                      </span>
                      <span class="text-sm text-amber-600 dark:text-amber-500">
                        bàn
                      </span>
                    </div>
                  </div>
                </template>
              </Column>
              <!-- Tổng tiền -->
              <Column field="total" header="TỔNG TIỀN" style="min-width: 10px">
                <template #body="{ data }">
                  <div class="py-2 space-y-0.5">
                    <!-- Tổng tiền -->
                    <p
                      class="text-base font-bold text-emerald-600 dark:text-emerald-400"
                    >
                      {{ formatCurrency(data.total_amount) }}đ
                    </p>
                  </div>
                </template>
              </Column>

              <!-- Trạng thái -->
              <Column
                field="status"
                header="Trạng thái"
                style="min-width: 130px"
              >
                <template #body="{ data }">
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

          <!-- Pagination -->
          <div
            class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50"
          >
            <div
              class="flex flex-col sm:flex-row justify-between items-center gap-4"
            >
              <div class="text-sm text-gray-600 dark:text-gray-400">
                Hiển thị
                <span class="font-semibold text-gray-900 dark:text-white">
                  {{ (currentPage - 1) * pageSize + 1 }}
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
    </div>
  </div>
</template>