<script setup>
import MultiSelect from 'primevue/multiselect';
import { useToast } from 'primevue/usetoast';
import { computed, watch } from 'vue';

const toast = useToast()

const props = defineProps({
    modelValue: {
        type: [Array, null],
        default: () => []
    },
    voucher: {
        type: Array,
        required: true
    },
    soBan:{
      type: [Number, String]
    }
})
const emit = defineEmits(['update:modelValue'])

const voucherData = computed({
    get: () => props.modelValue,
    set: (value) =>emit('update:modelValue', value)
})

const filteredVoucher = computed(() =>{
  if(!props.soBan) return []

  return props.voucher.filter(v => 
    Number(props.soBan) >= Number(v.conditions.min_tables)
  )
})

watch(voucherData, (newVal, oldVal) => {
  if(newVal.length > 2) {
    voucherData.value = oldVal
    toast.add({
        severity: 'warn',
        summary: 'Giới hạn voucher',
        detail: 'Chỉ được chọn tối đa 2 voucher',
        life: 3000
    })
  }
})

watch(() => props.soBan, () =>{
  voucherData.value = []
})
</script>

<template>
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl">
        <!-- Main Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <!-- Header Section -->
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Voucher - Ưu đãi</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tối đa 2 voucher</p>
                        </div>
                    </div>
                    <div v-if="soBan >= 50" class="flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-xs font-medium text-green-700 dark:text-green-300">Đủ điều kiện</span>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <MultiSelect
                    v-model="voucherData"
                    class="w-full"
                    placeholder="Chọn voucher ưu đãi"
                    :options="filteredVoucher"
                    option-label="name"
                    display="chip"
                    :show-clear="true"
                    filter
                    :max-selected-labels="2"
                    :disabled="soBan < 50"
                    :pt="{
                        root: {
                            class: 'w-full border border-gray-200 dark:border-gray-600 rounded-xl focus-within:ring-2 focus-within:ring-blue-500 transition-all dark:bg-gray-700'
                        },
                        label: {
                            class: 'px-4 py-3'
                        }
                    }"
                >
                    <template #option="{option}">
                        <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                        {{ option.code }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300 mb-1">
                                        {{ option.name }}
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Áp dụng từ {{ option.conditions.min_tables }} bàn</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </MultiSelect>

                <!-- Warning khi chưa đủ điều kiện -->
                <div v-if="soBan < 50" class="mt-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl p-4 border border-amber-200 dark:border-amber-900/30">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-sm text-amber-800 dark:text-amber-300">
                            <p class="font-medium">Chưa đủ điều kiện sử dụng voucher</p>
                            <p class="mt-1">Vui lòng đặt từ 50 bàn trở lên để được áp dụng ưu đãi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div v-if="voucherData.length > 0" class="px-6 pb-6">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 border border-green-100 dark:border-green-900/30">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-600 dark:bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Đã chọn</p>
                                <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                    {{ voucherData.length }} / 2 voucher
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>