<script setup lang="ts">
import AutoComplete from "primevue/autocomplete";
import Button from "primevue/button";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import Tag from "primevue/tag";
import { ref, onMounted, watch } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "primevue/usetoast";
import { getDishGroup, getDish } from "../../../services/dishService";

const router = useRouter();
const toast = useToast();

const loading = ref(false);                    // Đang load dữ liệu không?
const listDishGroup = ref<{ id: number; name: string }[]>([]); // Danh sách các danh mục (tab)
const listDish = ref<any[]>([]);               // Danh sách TẤT CẢ món ăn (gốc)
const displayedDish = ref<any[]>([]);          // Danh sách món đang HIỂN THỊ trên bảng (sau khi lọc)

const selectGroupId = ref<number | null>(null); // Danh mục đang chọn (null = Tất cả)
const searchQuery = ref('');                   // Nội dung trong ô tìm kiếm
const filteredSuggestions = ref<any[]>([]);    // Danh sách gợi ý khi gõ tìm kiếm

// Load data
onMounted(async () => {
  await loadData();
});

const loadData = async () => {
  loading.value = true;
  try {
    const [groupResp, dishResp] = await Promise.all([
      getDishGroup(),
      getDish()
    ]);

    listDishGroup.value = groupResp.data.data.items;
    listDish.value = dishResp.data.data;
    displayedDish.value = listDish.value;

    // Tự động chọn danh mục đầu tiên nếu chưa chọn
    if (listDishGroup.value.length > 0 && selectGroupId.value === null) {
      selectGroupId.value = listDishGroup.value[0].id;
    }
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Tải danh sách món ăn thất bại",
      life: 3000,
    });
  } finally {
    loading.value = false;
  }
};

const applyFilter = () => {
  let filtered = [...listDish.value];// Copy danh sách gốc

  // Lọc theo danh mục nếu có chọn
  if (selectGroupId.value !== null) {
    filtered = filtered.filter(d => d.dish_group_id === selectGroupId.value);
  }

  // Lọc theo từ khóa tìm kiếm nếu có gõ
  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase().trim();
    filtered = filtered.filter(d => d.name.toLowerCase().includes(q));
  }

  displayedDish.value = filtered;// Cập nhật bảng
};

watch([selectGroupId, searchQuery], applyFilter);

const searchSuggestions = (event: { query: string }) => {
  const query = event.query.trim().toLowerCase();
  if (query.length < 1) {
    filteredSuggestions.value = [];
    return;
  }
  filteredSuggestions.value = listDish.value
    .filter(d => d.name.toLowerCase().includes(query))
    .slice(0, 10);
};

const onDishSelect = (event: any) => {
  const selected = event.value;
  searchQuery.value = selected.name;     // Hiện tên món trong ô search
  displayedDish.value = [selected];      // Chỉ hiển thị đúng 1 món đó
  selectGroupId.value = null;            // Bỏ chọn tab danh mục
};

const clearSearch = () => {
  searchQuery.value = '';
  applyFilter();
};

const editDish = (event: any) => {
  const dishID = event.data.id
  router.push({ name: "dishDetail", params: { id: dishID } })
}
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900 p-6">
    <div class="max-w-7xl mx-auto">
      <!-- Header với animation -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 mb-6 animate-fade-in-down">
        <div class="flex justify-between items-center">
          <div class="space-y-2">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg animate-pulse-slow">
                <i class="pi pi-utensils text-white text-xl"></i>
              </div>
              <div>
                <h1 class="font-bold text-3xl bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent">
                  Quản lý món ăn
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  Quản lý và theo dõi tất cả món ăn của hệ thống
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Search với animation -->
        <div class="mt-6 animate-fade-in">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
              <i class="pi pi-search text-gray-400 transition-colors duration-300"></i>
            </div>
            <AutoComplete
              v-model="searchQuery"
              :suggestions="filteredSuggestions"
              @complete="searchSuggestions"
              @item-select="onDishSelect"
              @clear="clearSearch"
              @keyup.enter="applyFilter"
              class="w-full transition-all duration-300"
              optionLabel="name"
              display="chip"
              input-class="w-full !pl-12 !pr-4 !py-3 !rounded-xl !border-2 !border-gray-200 dark:!border-gray-600 focus:!border-indigo-500 dark:focus:!border-indigo-400 !shadow-sm !transition-all !duration-300 hover:!shadow-md"
              placeholder="Tìm kiếm món ăn theo tên..."
            >
              <template #option="{ option }">
                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 transform hover:scale-[1.02]">
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ option.name }}
                  </div>
                  <div class="text-sm text-gray-600 dark:text-gray-300 mt-1 truncate">
                    {{ option.description || 'Không có mô tả' }}
                  </div>
                </div>
              </template>
            </AutoComplete>
          </div>
        </div>
      </div>

      <!-- Tab nhóm món với animation -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6 animate-fade-in-up animation-delay-100">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <div class="flex flex-wrap gap-3">
            <Button
              label="Tất cả"
              :outlined="selectGroupId !== null"
              @click="selectGroupId = null"
              class="transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
            />
            <Button
              v-for="group in listDishGroup"
              :key="group.id"
              :label="group.name"
              :outlined="selectGroupId !== group.id"
              @click="selectGroupId = group.id"
              class="transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
            />
          </div>
        </div>
      </div>

      <!-- Table với animation -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden animate-fade-in-up animation-delay-200">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center transition-all duration-300 hover:scale-110 hover:rotate-12">
              <i class="pi pi-list text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <div>
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Danh sách món ăn
              </h2>
              <p class="text-sm text-gray-500 dark:text-gray-400 transition-all duration-300">
                {{ displayedDish.length }} món ăn
              </p>
            </div>
          </div>
        </div>

        <div class="overflow-x-auto px-4 py-2">
          <DataTable
            :value="displayedDish"
            :loading="loading"
            data-key="id"
            class="custom-datatable"
            row-hover
            @row-click="editDish"
          >
            <Column field="id" header="ID" style="min-width: 140px;">
              <template #body="{ data }">
                <div class="px-4 py-2.5 rounded-lg bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 border-2 border-blue-200 dark:border-blue-700 shadow-sm transition-all duration-300 hover:shadow-md hover:scale-105 cursor-pointer">
                  <span class="text-sm font-mono font-bold text-blue-700 dark:text-blue-300">
                    #{{ data.id }}
                  </span>
                </div>
              </template>
            </Column>

            <Column field="name" header="Tên món ăn" style="min-width: 300px;">
              <template #body="{ data }">
                <div class="flex items-center gap-4 py-2 transition-all duration-300 hover:translate-x-2">
                  <div class="flex-1 min-w-0">
                    <div class="font-semibold text-gray-900 dark:text-white transition-colors duration-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                      {{ data.name }}
                    </div>
                  </div>
                </div>
              </template>
            </Column>

            <Column header="Danh mục" style="min-width: 180px;">
              <template #body="{ data }">
                <span class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 transition-colors duration-300 hover:text-gray-900 dark:hover:text-white">
                  {{ data.dish_group_name || '-' }}
                </span>
              </template>
            </Column>

            <Column field="description" header="Mô tả" style="min-width: 300px;">
              <template #body="{ data }">
                <span class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 transition-colors duration-300 hover:text-gray-900 dark:hover:text-white">
                  {{ data.description || '-' }}
                </span>
              </template>
            </Column>
          </DataTable>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Animation keyframes */
@keyframes fade-in-down {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes pulse-slow {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}

/* Animation classes */
.animate-fade-in-down {
  animation: fade-in-down 0.6s ease-out;
}

.animate-fade-in-up {
  animation: fade-in-up 0.6s ease-out;
}

.animate-fade-in {
  animation: fade-in 0.6s ease-out;
}

.animate-pulse-slow {
  animation: pulse-slow 3s ease-in-out infinite;
}

/* Animation delays */
.animation-delay-100 {
  animation-delay: 0.1s;
  animation-fill-mode: backwards;
}

.animation-delay-200 {
  animation-delay: 0.2s;
  animation-fill-mode: backwards;
}

/* Custom DataTable styling với transitions */
:deep(.custom-datatable) {
  .p-datatable-tbody > tr {
    transition: all 0.3s ease;
  }
  
  .p-datatable-tbody > tr:hover {
    background-color: rgba(99, 102, 241, 0.05);
    transform: scale(1.01);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  }
  
  .p-datatable-tbody > tr > td {
    transition: all 0.3s ease;
  }
}

/* Smooth scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.05);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: rgba(99, 102, 241, 0.3);
  border-radius: 10px;
  transition: background 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(99, 102, 241, 0.5);
}
</style>