<script setup>
import { ref, computed, watch } from 'vue'
import { LunarDate } from 'lunar-date-vn'
import Dropdown from 'primevue/dropdown'

const props = defineProps({
  modelValue: {
    type: Object,
    default: () => ({
      lunarDay: '',
      lunarMonth: '',
      lunarYear: '',
      isLeapMonth: false,
      session: ''
    })
  }
})

const emit = defineEmits(['update:modelValue'])

const scheduleData = computed({
  get: () => props.modelValue,
  set: (newValue) => emit('update:modelValue', newValue)
})

const canChiYears = [
  { year: 2025, canChi: 'Ất Tỵ', label: 'Ất Tỵ (2025)' },
  { year: 2026, canChi: 'Bính Ngọ', label: 'Bính Ngọ (2026)' },
  { year: 2027, canChi: 'Đinh Mùi', label: 'Đinh Mùi (2027)' },
  { year: 2028, canChi: 'Mậu Thân', label: 'Mậu Thân (2028)' },
  { year: 2029, canChi: 'Kỷ Dậu', label: 'Kỷ Dậu (2029)' },
  { year: 2030, canChi: 'Canh Tuất', label: 'Canh Tuất (2030)' },
]

const defaultYear = canChiYears.find(y => y.year === 2026) || canChiYears[1]

const selectedYear = ref(defaultYear)
const selectedLunarMonth = ref(scheduleData.value.lunarMonth || 1)
const selectedLunarDay = ref(scheduleData.value.lunarDay || 1)
const isLeapMonth = ref(scheduleData.value.isLeapMonth || false)
const session = ref(scheduleData.value.session || 'trưa')

const months = computed(() => 
  Array.from({ length: 12 }, (_, i) => ({ 
    value: i + 1, 
    label: `Tháng ${i + 1}` 
  }))
)

const days = ref(Array.from({ length: 31 }, (_, i) => ({ 
  value: i + 1, 
  label: `Ngày ${i + 1}` 
})))

const solarDate = computed(() => {
  try {
    const lunar = new LunarDate({
      year: selectedYear.value.year,
      month: selectedLunarMonth.value,
      day: selectedLunarDay.value,
      leap_month: isLeapMonth.value
    })
    lunar.init()

    const solar = lunar.toSolarDate()
    return {
      day: solar.day.toString().padStart(2, '0'),
      month: solar.month.toString().padStart(2, '0'),
      year: solar.year,
      full: `${solar.day.toString().padStart(2, '0')}/${solar.month.toString().padStart(2, '0')}/${solar.year}`
    }
  } catch (e) {
    console.error('Lỗi chuyển đổi ngày âm:', e)
    return { full: 'Ngày không hợp lệ' }
  }
})

watch(
  () => props.modelValue,
  (val) => {
    if (!val) return

    selectedLunarDay.value = val.lunarDay ?? 1
    selectedLunarMonth.value = val.lunarMonth ?? 1
    isLeapMonth.value = !!val.isLeapMonth
    session.value = val.session || 'trưa'

    if (val.lunarYear) {
      const found = canChiYears.find(y => y.year === val.lunarYear)
      if (found) selectedYear.value = found
    }
  },
  { immediate: true }
)

watch([() => selectedYear.value, selectedLunarMonth, isLeapMonth], () => {
  try {
    let maxDay = 29
    for (let d = 29; d <= 31; d++) {
      const testLunar = new LunarDate({
        year: selectedYear.value.year,
        month: selectedLunarMonth.value,
        day: d,
        leap_month: isLeapMonth.value
      })
      testLunar.init()
      try {
        testLunar.toSolarDate()
        maxDay = d
      } catch {
        break
      }
    }

    days.value = Array.from({ length: maxDay }, (_, i) => ({ 
      value: i + 1, 
      label: `Ngày ${i + 1}` 
    }))

    if (selectedLunarDay.value > maxDay) {
      selectedLunarDay.value = maxDay
    }
  } catch {
    days.value = Array.from({ length: 29 }, (_, i) => ({ 
      value: i + 1, 
      label: `Ngày ${i + 1}` 
    }))
  }
}, { immediate: true })

watch(
  [selectedLunarDay, selectedLunarMonth, () => selectedYear.value, isLeapMonth, session, solarDate],
  () => {
    scheduleData.value = {
      lunarDay: selectedLunarDay.value,
      lunarMonth: selectedLunarMonth.value,
      lunarYear: selectedYear.value.year,
      lunarCanChi: selectedYear.value.canChi,
      isLeapMonth: isLeapMonth.value,
      session: session.value,
      solarDate: `${solarDate.value.year}-${solarDate.value.month}-${solarDate.value.day}`,
      solarDateObj: solarDate.value
    }
  },
  { immediate: true }
)

</script>

<template>
  <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl">
    <!-- Header -->
    <div class="mb-6 text-center">
      <h1 class="text-2xl font-bold text-indigo-900 dark:text-indigo-300 mb-1">
        Thời gian tổ chức
      </h1>
      <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">THANH AN HỘI</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
      <!-- Section: Ngày giờ âm lịch -->
      <div class="p-6 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Ngày giờ âm lịch</h2>
        </div>

        <div class="space-y-5">
          <!-- Ngày âm -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Ngày âm <span class="text-red-500">*</span>
            </label>
            <Dropdown
              v-model="selectedLunarDay"
              :options="days"
              option-label="label"
              option-value="value"
              placeholder="Chọn ngày"
              class="w-full"
              :pt="{
                root: {
                  class: 'w-full border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all dark:bg-gray-700'
                }
              }"
            />
          </div>

          <!-- Tháng âm -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Tháng âm <span class="text-red-500">*</span>
            </label>
            <Dropdown
              v-model="selectedLunarMonth"
              :options="months"
              option-label="label"
              option-value="value"
              placeholder="Chọn tháng"
              class="w-full"
              :pt="{
                root: {
                  class: 'w-full border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all dark:bg-gray-700'
                }
              }"
            />
          </div>

          <!-- Năm âm -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Năm âm <span class="text-red-500">*</span>
            </label>
            <Dropdown
              v-model="selectedYear"
              :options="canChiYears"
              option-label="label"
              placeholder="Chọn năm"
              class="w-full"
              :pt="{
                root: {
                  class: 'w-full border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all dark:bg-gray-700'
                }
              }"
            />
          </div>

          <!-- Tháng nhuận -->
          <div>
            <label class="flex items-center gap-2 cursor-pointer">
              <input 
                type="checkbox" 
                v-model="isLeapMonth"
                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
              />
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tháng nhuận</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Section: Buổi tổ chức -->
      <div class="p-6 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Buổi tổ chức</h2>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <!-- Buổi trưa -->
          <label 
            class="relative flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all"
            :class="session === 'trua' 
              ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' 
              : 'border-gray-200 dark:border-gray-600 hover:border-blue-300'"
          >
            <input 
              type="radio" 
              v-model="session" 
              value="trua"
              class="w-5 h-5 text-blue-600 focus:ring-blue-500"
            />
            <div class="flex-1">
              <span class="font-semibold text-gray-900 dark:text-white">Buổi trưa</span>
              <p class="text-xs text-gray-500 dark:text-gray-400">11:00 - 14:00</p>
            </div>
          </label>

          <!-- Buổi chiều -->
          <label 
            class="relative flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all"
            :class="session === 'chieu' 
              ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' 
              : 'border-gray-200 dark:border-gray-600 hover:border-blue-300'"
          >
            <input 
              type="radio" 
              v-model="session" 
              value="chieu"
              class="w-5 h-5 text-blue-600 focus:ring-blue-500"
            />
            <div class="flex-1">
              <span class="font-semibold text-gray-900 dark:text-white">Buổi chiều</span>
              <p class="text-xs text-gray-500 dark:text-gray-400">17:00 - 21:00</p>
            </div>
          </label>
        </div>
      </div>

      <!-- Ngày dương lịch -->
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-indigo-900/20 dark:to-blue-900/20 p-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-blue-600 dark:bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Ngày dương lịch</p>
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                {{ solarDate.full }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>