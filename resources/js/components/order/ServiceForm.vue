<script setup>
import { Icon } from "@iconify/vue";
import MultiSelect from "primevue/multiselect";
import { computed } from "vue";

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
  dish: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["update:modelValue"]);

const dishData = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});
const removeDish = (itemToRemove) => {
  dishData.value = dishData.value.filter((item) => item !== itemToRemove);
};
</script>

<template>
  <div
    class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl"
  >
    <!-- Header -->
    <div class="mb-6 text-center">
      <h1 class="text-2xl font-bold text-indigo-900 dark:text-indigo-300 mb-1">
        Thông tin dịch vụ
      </h1>
      <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">
        THANH AN HỘI
      </p>
    </div>

    <!-- Main Card -->
    <div
      class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden"
    >
      <!-- Section: Chọn món ăn -->
      <div class="p-6 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3 mb-6">
          <div
            class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center"
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
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
              />
            </svg>
          </div>
          <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            Chọn món ăn
          </h2>
        </div>

        <div class="space-y-5">
          <!-- MultiSelect món ăn -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
              Thực đơn <span class="text-red-500">*</span>
            </label>
            <MultiSelect
              v-model="dishData"
              placeholder="Tìm kiếm và chọn món ăn..."
              required
              :options="dish"
              option-label="name"
              display="chip"
              filter
              :show-clear="true"
              class="w-full"
              filter-placeholder="Tìm kiếm món..."
              :pt="{
                root: {
                  class:
                    'w-full border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all dark:bg-gray-700',
                },
              }"
            >
              <template #option="{ option }">
                <div
                  class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors"
                >
                  <div class="flex items-start gap-3">
                    <div
                      class="w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0"
                    >
                      <i
                        class="pi pi-star-fill text-orange-600 dark:text-orange-400 text-sm"
                      ></i>
                    </div>
                    <div class="flex-1">
                      <div
                        class="font-medium text-gray-900 dark:text-white mb-1"
                      >
                        {{ option.name }}
                      </div>
                      <div class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="inline-flex items-center gap-1">
                          <i class="pi pi-tag text-xs"></i>
                          {{ option.dish_group_name }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </template>

              <template #chip="{ value }">
                <div
                  class="inline-flex items-center gap-2 px-3 py-1.5 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 rounded-lg"
                >
                  <i class="pi pi-check-circle text-xs"></i>
                  <span class="text-sm font-medium">{{ value.name }}</span>
                </div>
              </template>

              <template #empty>
                <div class="p-6 text-center">
                  <div
                    class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
                  >
                    <i
                      class="pi pi-search text-gray-400 dark:text-gray-500 text-2xl"
                    ></i>
                  </div>
                  <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Không tìm thấy món ăn nào
                  </p>
                </div>
              </template>
            </MultiSelect>
          </div>
        </div>
      </div>

      <!-- Tổng số món đã chọn -->
      <div
        v-if="dishData && dishData.length > 0"
        class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-indigo-900/20 dark:to-blue-900/20 p-6"
      >
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-3">
            <div
              class="w-12 h-12 bg-blue-600 dark:bg-blue-500 rounded-xl flex items-center justify-center shadow-lg"
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
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                Tổng số món
              </p>
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                {{ dishData.length }} món
              </p>
            </div>
          </div>
        </div>

        <!-- Danh sách món đã chọn - THÊM NÚT XÓA Ở ĐÂY -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
          <div
            v-for="(item, index) in dishData"
            :key="index"
            class="flex items-center justify-between gap-2 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group"
          >
            <div class="flex items-center gap-2 flex-1">
              <span class="text-blue-600 dark:text-blue-400 font-medium"
                >{{ index + 1 }}.</span
              >
              <span class="font-medium">{{ item.name }}</span>
              <span class="text-gray-400 text-xs">·</span>
              <span class="text-gray-500 dark:text-gray-400 text-xs">{{
                item.dish_group_name
              }}</span>
            </div>

            <!-- Nút xóa từng món (góc phải) -->
            <button
              type="button"
              @click="removeDish(item)"
              class="text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400 transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100"
              aria-label="Xóa món này"
            >
              <i class="pi pi-times-circle text-lg"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>