<script setup>
import InputNumber from "primevue/inputnumber";
import { computed, watch } from "vue";

const props = defineProps({
  modelValue: {
    type: Object,
    required: false,
    default: () => ({ soBan: "", donGia: "", tienCoc: "" }),
  },
});

const emit = defineEmits(["update:modelValue", "update:tongTien"]);

const tableData = computed({
  get: () => props.modelValue ?? { soBan: "", donGia: "", tienCoc: "" },
  set: (newValue) => emit("update:modelValue", newValue),
});

// Tổng tiền (chưa trừ cọc)
const tongTien = computed(() => {
  const soBan = Number(tableData.value?.soBan) || 0;
  const donGia = Number(tableData.value?.donGia) || 0;
  return soBan * donGia;
});

// Tiền còn phải thanh toán = Tổng tiền - Tiền cọc
const conPhaiThanhToan = computed(() => {
  const coc = Number(tableData.value?.tienCoc) || 0;
  return Math.max(tongTien.value - coc, 0); // không âm
});

// Format số tiền
const formatCurrency = (value) => {
  return Number(value || 0).toLocaleString("vi-VN");
};

// Emit tổng tiền (chưa trừ cọc) lên parent để dùng ở preview
watch(tongTien, (value) => {
  emit("update:tongTien", value);
});
</script>

<template>
  <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl">
    <!-- Header -->
    <div class="mb-6 text-center">
      <h1 class="text-2xl font-bold text-indigo-900 dark:text-indigo-300 mb-1">
        Dịch vụ tiệc cưới
      </h1>
      <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">THANH AN HỘI</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
      <!-- Section: Số bàn & Giá -->
      <div class="p-6 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
          </div>
          <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Số bàn & Giá</h2>
        </div>

        <div class="space-y-5">
          <!-- Số bàn -->
          <div>
            <label for="soBan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Số bàn <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <InputNumber
                v-model.number="tableData.soBan"
                inputId="soBan"
                placeholder="Nhập số bàn"
                class="w-full"
                :min="1"
                :pt="{ input: { class: 'w-full px-4 py-3 pr-16 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all dark:bg-gray-700 dark:text-gray-100' } }"
              />
              <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm pointer-events-none">
                bàn
              </span>
            </div>
          </div>

          <!-- Đơn giá / bàn -->
          <div>
            <label for="donGia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Đơn giá / bàn <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <InputNumber
                v-model="tableData.donGia"
                inputId="donGia"
                placeholder="Ví dụ: 2.500.000"
                class="w-full"
                :min="0"
                locale="vi-VN"
                mode="currency"
                currency="VND"
                :pt="{ input: { class: 'w-full px-4 py-3 pr-16 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all dark:bg-gray-700 dark:text-gray-100' } }"
              />
              <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm pointer-events-none">
                đ
              </span>
            </div>
          </div>

          <!-- Tiền cọc -->
          <div>
            <label for="tienCoc" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Tiền cọc <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <InputNumber
                v-model="tableData.tienCoc"
                inputId="tienCoc"
                placeholder="Ví dụ: 50.000.000"
                class="w-full"
                :min="0"
                locale="vi-VN"
                mode="currency"
                currency="VND"
                :pt="{ input: { class: 'w-full px-4 py-3 pr-16 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all dark:bg-gray-700 dark:text-gray-100' } }"
              />
              <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm pointer-events-none">
                đ
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Tổng kết thanh toán -->
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-indigo-900/20 dark:to-blue-900/20 p-6 space-y-4">
        <!-- Tổng tiền -->
        <div class="flex justify-between items-center">
          <span class="text-lg font-medium text-gray-700 dark:text-gray-300">Tổng tiền</span>
          <span class="text-xl font-bold text-gray-900 dark:text-white">
            {{ formatCurrency(tongTien) }} đ
          </span>
        </div>

        <!-- Tiền cọc -->
        <div class="flex justify-between items-center">
          <span class="text-base text-gray-600 dark:text-gray-400">Đã cọc</span>
          <span class="text-lg font-semibold text-green-600 dark:text-green-400">
            - {{ formatCurrency(tableData.tienCoc) }} đ
          </span>
        </div>

        <!-- Đường kẻ phân cách -->
        <hr class="border-t-2 border-dashed border-blue-300 dark:border-blue-700" />

        <!-- Còn phải thanh toán -->
        <div class="flex justify-between items-center pt-2">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-blue-600 dark:bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Còn phải thanh toán</p>
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                {{ formatCurrency(conPhaiThanhToan) }} đ
              </p>
            </div>
          </div>
        </div>

        <!-- Chi tiết tính toán nhỏ -->
        <div v-if="tableData.soBan && tableData.donGia" class="text-right text-sm text-gray-500 dark:text-gray-400">
          {{ tableData.soBan }} bàn × {{ formatCurrency(tableData.donGia) }} đ/bàn
        </div>
      </div>
    </div>
  </div>
</template>