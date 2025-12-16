<script setup lang="ts">
import AutoComplete from "primevue/autocomplete";
import Button from "primevue/button";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import Paginator from "primevue/paginator";
import { computed, onMounted, ref } from "vue";
import { getDish, getDishGroup } from "../../../services/dishService";
import { useToast } from "primevue/usetoast";

const loading = ref(false);
const listDish = ref([]);
const listDishGroup = ref([]);
const toast = useToast();
const selectGroupId = ref(null);
const pageSize = ref(20)
const totalService = ref(0)
const currentPage = ref(1)
const searchQuery = ref('')

onMounted(async () => {
  await loadData();
});

const onPageChange = (event:any) => {
  currentPage.value = event.page + 1
  pageSize.value = event.rows

  loadData();
}

const filteredDishes = computed(() => {
  if (!selectGroupId.value) return listDish.value;
  return listDish.value.filter(
    (dish:any) => dish.service_group_id == selectGroupId.value
  );
});

const loadData = async () => {
  loading.value = true;
  try {
    const [respDish, respGroup] = await Promise.all([
      getDish(),
      getDishGroup(),
    ]);
    listDish.value = respDish.data.data.data;
    listDishGroup.value = respGroup.data.data.data;
    // Tự động chọn tab đầu tiên nếu chưa chọn
    if (listDishGroup.value.length > 0 && !selectGroupId.value) {
      selectGroupId.value = listDishGroup.value[0].id;
    }
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Không thể tải dữ liệu món ăn",
      life: 3000,
    });
  } finally {
    loading.value = false;
  }
};
</script>
<template>
  <div
    class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900 p-4 md:p-6 lg:p-8"
  >
    <div class="max-w-7xl mx-auto">
      <!-- Header Card -->
      <div
        class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8 mb-6 backdrop-blur-sm bg-opacity-90"
      >
        <div
          class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6"
        >
          <div class="space-y-3">
            <div class="flex items-center gap-4">
              <div
                class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg transform hover:scale-105 transition-transform"
              >
                <i class="pi pi-book text-white text-2xl"></i>
              </div>
              <div>
                <h1
                  class="font-bold text-3xl md:text-4xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent"
                >
                  Quản lý món ăn
                </h1>
                <p
                  class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-2"
                >
                  <i class="pi pi-chart-line text-xs"></i>
                  Quản lý và theo dõi món ăn
                </p>
              </div>
            </div>
          </div>
          <Button
            label="Tạo món ăn"
            icon="pi pi-plus"
            class="p-button-rounded !bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:from-indigo-600 hover:via-purple-600 hover:to-pink-600 !text-white !border-0 !shadow-lg hover:!shadow-xl transition-all duration-300 transform hover:scale-105 !px-6 !py-3 !font-semibold"
          />
        </div>

        <!-- Search Box -->
        <div class="mt-8">
          <div class="relative group">
            <div
              class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none z-10"
            >
              <i
                class="pi pi-search text-gray-400 group-focus-within:text-indigo-500 transition-colors"
              ></i>
            </div>
            <AutoComplete
              class="w-full"
              input-class="w-full !pl-14 !pr-4 !py-4 !rounded-2xl !border-2 !border-gray-200 dark:!border-gray-600 focus:!border-indigo-500 dark:focus:!border-indigo-400 !shadow-md focus:!shadow-lg hover:!shadow-lg !transition-all !duration-300 !bg-white dark:!bg-gray-700 !text-gray-900 dark:!text-white"
              placeholder="Tìm kiếm theo tên món ăn..."
            >
              <template #option="{ option }">
                <div
                  class="p-3 hover:bg-indigo-50 dark:hover:bg-gray-600 rounded-xl transition-all duration-200 cursor-pointer"
                >
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ option.name }}
                  </div>
                </div>
              </template>
            </AutoComplete>
          </div>
        </div>
      </div>

      <!-- Content Card -->
      <div
        class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden backdrop-blur-sm bg-opacity-90"
      >
        <div
          class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700"
        >
          <div class="flex flex-wrap gap-3">
            <button
              class="px-8 py-2.5 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
              :class="
                !selectGroupId
                  ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white'
                  : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600'
              "
              @click="selectGroupId = null"
            >
              <i class="pi pi-th-large mr-2"></i>
              TẤT CẢ
            </button>
            <template v-for="(value, index) in listDishGroup" :key="index">
              <button
                class="px-7 py-2.5 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                :class="
                  selectGroupId === value.id
                    ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white'
                    : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600'
                "
                @click="selectGroupId = value.id"
              >
                <i class="pi pi-tag mr-2"></i>
                {{ value.name }}
              </button>
            </template>
          </div>
        </div>

        <div class="overflow-x-auto px-4 py-2">
          <DataTable
            :value="filteredDishes"
            :loading="loading"
            data-key="id"
            class="custom-datatable"
            row-hover
            selection-mode="single"
            @row-click="editStaff"
          >
            <Column field="id" header="ID" style="min-width: 140px">
              <template #body="{ data }">
                <div class="flex items-center gap-2 py-2">
                  <div
                    class="px-4 py-2.5 rounded-lg bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 border-2 border-blue-200 dark:border-blue-700 shadow-sm"
                  >
                    <span
                      class="text-sm font-mono font-bold text-blue-700 dark:text-blue-300"
                    >
                      #{{ data.id }}
                    </span>
                  </div>
                </div>
              </template>
            </Column>

            <Column
              field="name"
              header="Tên món ăn"
              :sortable="true"
              style="min-width: 200px"
            >
              <template #body="{ data }">
                <div class="flex items-center gap-4 py-2">
                  <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-md flex-shrink-0"
                  >
                    <i class="pi pi-book text-white text-base"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div
                      class="font-semibold text-gray-900 dark:text-white text-base"
                    >
                      {{ data.name }}
                    </div>
                  </div>
                </div>
              </template>
            </Column>
            <Column
              field="service_group_id"
              header="Danh mục"
              style="min-width: 180px"
              :sortable="true"
            >
              <template #body="{ data }">
                <div class="py-3">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/40 dark:to-pink-900/40 flex items-center justify-center flex-shrink-0 shadow-md"
                    >
                      <i
                        class="pi pi-pen-to-square text-purple-600 dark:text-purple-400 text-lg"
                      ></i>
                    </div>
                    <span
                      class="text-sm font-bold text-purple-700 dark:text-purple-300"
                    >
                      {{ data.group_name }}
                    </span>
                  </div>
                </div>
              </template>
            </Column>
            <Column
              field="description"
              header="Mô tả"
              :sortable="true"
              style="min-width: 200px"
            >
              <template #body="{ data }">
                <div class="py-2">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0"
                    >
                      <i
                        class="pi pi-book text-green-600 dark:text-green-400 text-sm"
                      ></i>
                    </div>
                    <span
                      class="text-sm font-medium text-gray-700 dark:text-gray-300 font-mono"
                    >
                      {{ data.description }}
                    </span>
                  </div>
                </div>
              </template>
            </Column>
          </DataTable>
        </div>

        <!-- Footer with Pagination -->
        <div
          class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700"
        >
          <div
            class="flex flex-col sm:flex-row justify-between items-center gap-4"
          >
            <div
              class="flex items-center gap-3 bg-white dark:bg-gray-700 px-5 py-3 rounded-xl shadow-md"
            >
              <div
                class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 flex items-center justify-center"
              >
                <i
                  class="pi pi-info-circle text-indigo-600 dark:text-indigo-400 text-lg"
                ></i>
              </div>
              <span
                class="text-sm font-medium text-gray-700 dark:text-gray-300"
              >
                Hiển thị
                <span
                  class="text-indigo-600 dark:text-indigo-400 font-bold text-base"
                  >{{ ((currentPage - 1) * pageSize) + 1 }}</span
                >
                đến
                <span
                  class="text-indigo-600 dark:text-indigo-400 font-bold text-base"
                  > {{ Math.min(currentPage * pageSize, totalService || 0) }}</span
                >
                trong tổng số
                <span
                  class="text-indigo-600 dark:text-indigo-400 font-bold text-base"
                  > {{ totalService || 0 }}</span
                >
                món ăn
              </span>
            </div>
            <Paginator
              :rows="pageSize"
              :total-records="totalService"
              :rows-per-page-options="[10, 20, 30, 50, 100]"
              class="custom-paginator"
              @page="onPageChange"
            />
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
  background: #f1f5f9;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(to bottom, #6366f1, #a855f7);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to bottom, #4f46e5, #9333ea);
}

/* Button hover effects */
button {
  position: relative;
  overflow: hidden;
}

button::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.2);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

button:hover::before {
  width: 300px;
  height: 300px;
}
</style>