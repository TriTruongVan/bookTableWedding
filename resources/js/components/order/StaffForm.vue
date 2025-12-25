<script setup>
import MultiSelect from 'primevue/multiselect';
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: [Array, null],
        default: () => []
    },
    staff: {
        type: Array,
        required: true
    }
})

const emit = defineEmits(['update:modelValue'])

const staffData = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
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
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Phân công nhân viên</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Chọn nhân viên phục vụ</p>
                        </div>
                    </div>
                    <div v-if="staffData.length > 0" class="flex items-center gap-2 px-3 py-1.5 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                        </svg>
                        <span class="text-xs font-medium text-indigo-700 dark:text-indigo-300">{{ staffData.length }}</span>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <MultiSelect
                    v-model="staffData"
                    class="w-full"
                    placeholder="Chọn nhân viên phục vụ"
                    :options="staff"
                    option-label="name"
                    display="chip"
                    filter
                    :show-clear="true"
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
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <span class="text-white font-semibold text-sm">
                                        {{ option.name.charAt(0).toUpperCase() }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ option.name }}
                                    </div>
                                    <div class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span>{{ option.tel }}</span>
                                    </div>
                                </div>
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            </div>
                        </div>
                    </template>
                </MultiSelect>
            </div>

            <!-- Selected Staff Info -->
            <div v-if="staffData.length > 0" class="px-6 pb-6">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl p-4 border border-indigo-100 dark:border-indigo-900/30">
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                        <p class="text-sm font-medium text-indigo-900 dark:text-indigo-300">
                            Đã chọn {{ staffData.length }} nhân viên
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <div
                            v-for="person in staffData"
                            :key="person.id"
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-700 rounded-lg border border-indigo-200 dark:border-indigo-800"
                        >
                            <div class="w-6 h-6 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-xs">
                                    {{ person.name.charAt(0).toUpperCase() }}
                                </span>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ person.name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="px-6 pb-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-900/30">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-sm text-blue-800 dark:text-blue-300">
                            <p class="font-medium mb-1">Lưu ý:</p>
                            <p>Nhân viên được chọn sẽ chịu trách nhiệm phục vụ và điều phối trong suốt sự kiện.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>