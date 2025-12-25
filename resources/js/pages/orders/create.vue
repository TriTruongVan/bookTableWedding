<script setup>
import { useRouter } from "vue-router";
import Button from "primevue/button";
import Card from "primevue/card";
import { Icon } from "@iconify/vue";
import CustomerForm from "@/components/order/CustomerForm.vue";
import { computed, onMounted, ref, watch } from "vue";
import { getAllWards } from "../../../services/userService";
import { useToast } from "primevue/usetoast";
import ScheduleAtForm from "@/components/order/ScheduleAtForm.vue";
import TableAndPriceForm from "@/components/order/TableAndPriceForm.vue";
import { getAllStaff } from "../../../services/staffService";
import StaffForm from "@/components/order/StaffForm.vue";
import VoucherForm from "@/components/order/VoucherForm.vue";
import { getAllVouchers } from "../../../services/voucherService";
import ServiceForm from "@/components/order/ServiceForm.vue";
import { getDish } from "../../../services/dishService";
import NoteForm from "@/components/order/NoteForm.vue";

const loading = ref(false);
const toast = useToast();
const wards = ref([]);
const staff = ref([]);
const voucher = ref([]);
const dish = ref([]);
const router = useRouter();

const customerData = ref({
  name: "",
  tel: "",
  street: "",
  ward: "",
});
const soBanGia = ref({
  soBan: "",
  donGia: "",
});
const schedule = ref({});
const tongTien = ref(0);
const note = ref("");
const staffData = ref({});
const voucherData = ref({});
const dishData = ref({});

onMounted(async () => {
  await loadWard();
  await loadStaff();
  await loadVoucher();
  await loadDish();
});

const loadDish = async () => {
  loading.value = true;
  try {
    const respDish = await getDish();
    dish.value = respDish.data.data;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Cập nhật thất bại",
      life: 3000,
    });
  } finally {
    loading.value = false;
  }
};

const loadStaff = async () => {
  loading.value = true;
  try {
    const respStaff = await getAllStaff();
    staff.value = respStaff.data.data.data;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Cập nhật thất bại",
      life: 3000,
    });
  } finally {
    loading.value = false;
  }
};

const loadVoucher = async () => {
  loading.value = true;
  try {
    const respVoucher = await getAllVouchers();
    voucher.value = respVoucher.data.data.items;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Cập nhật thất bại",
      life: 3000,
    });
  } finally {
    loading.value = false;
  }
};

const loadWard = async () => {
  loading.value = true;
  try {
    const respWard = await getAllWards();
    wards.value = respWard.data.data.wards;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Cập nhật thất bại",
      life: 3000,
    });
  } finally {
    loading.value = false;
  }
};

const getWardLabel = (wardId) => {
  if (!wardId) return null;
  const found = wards.value.find(w => w.id === wardId);
  return found ? found.label : null;
};

const canShowVoucher = computed(() =>{
  const soBan = Number(soBanGia.value.soBan) || 0
  return soBan >= 50
})
const resetForm = () => {
  // Reset customer
  customerData.value = {
    name: "",
    tel: "",
    street: "",
    ward: "",
  };

  // Reset số bàn & giá
  soBanGia.value = {
    soBan: "",
    donGia: "",
  };

  // Reset các phần khác
  schedule.value = {};
  tongTien.value = 0;
  note.value = "";
  staffData.value = [];
  voucherData.value = [];
  dishData.value = []; // hoặc [] nếu là mảng

  // Optional: toast thông báo reset thành công
  toast.add({
    severity: "info",
    summary: "Đã làm mới",
    detail: "Form đã được reset về mặc định",
    life: 3000,
  });
};
</script>

<template>
  <div
    class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900 p-6"
  >
    <div class="max-w-7xl mx-auto">
      <!-- Header Section with Enhanced Design -->
      <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 mb-6"
      >
        <div class="flex items-center gap-4">
          <button
            @click="router.push('/orders')"
            class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500 flex items-center justify-center shadow-lg transition-all duration-200 hover:scale-105"
            aria-label="Quay lại"
          >
            <i
              class="pi pi-arrow-left text-gray-700 dark:text-gray-200 text-lg"
            ></i>
          </button>

          <div class="flex items-center gap-3 flex-1">
            <div
              class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg"
            >
              <i class="pi pi-shopping-cart text-white text-xl"></i>
            </div>
            <div>
              <h1
                class="font-bold text-3xl bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent"
              >
                Tạo đơn hàng Mới
              </h1>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Nhập thông tin đơn hàng mới vào hệ thống
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Card -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Card Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                  <i class="pi pi-file-edit text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                  <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Chi tiết đơn hàng
                  </h2>
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    Điền đầy đủ thông tin bên dưới
                  </p>
                </div>
              </div>
            </div>

            <!-- Form Content -->
            <form class="p-8">
              <div class="space-y-8">
                <CustomerForm v-model="customerData" :wards="wards" />
                <ServiceForm :dish="dish" v-model="dishData" />
                <ScheduleAtForm v-model="schedule" />
                <TableAndPriceForm
                  v-model="soBanGia"
                  @update:tongTien="tongTien = $event"
                />
                <NoteForm v-model="note" />
                <VoucherForm
                  v-if="canShowVoucher"
                  :voucher="voucher"
                  v-model="voucherData"
                  :soBan="soBanGia.soBan"
                />
                <StaffForm :staff="staff" v-model="staffData" />

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                  <button
                    type="button"
                    outlined
                    @click="resetForm"
                    class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md"
                  >
                    <i class="pi pi-refresh mr-2"></i>
                    Làm mới
                  </button>
                  <button
                    type="submit"
                    :loading="loading"
                    class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl hover:opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <i class="pi pi-check mr-2"></i>
                    {{ loading ? 'Đang tạo...' : 'Tạo đơn hàng' }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Preview Sidebar -->
        <div class="lg:col-span-1 space-y-6">
          <!-- Order Summary Card -->
          <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-6"
          >
            <!-- Header -->
            <div class="p-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-blue-200 dark:border-blue-800">
              <div class="text-center">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                  Dịch vụ tiệc cưới
                </h2>
                <h3 class="text-xl font-bold text-blue-800 dark:text-blue-400 mt-1">
                  THANH AN HỘI
                </h3>
              </div>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-4">
              <!-- Khách hàng -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <i class="pi pi-user text-emerald-600 dark:text-emerald-400 text-xs"></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">Khách hàng</p>
                </div>
                <div class="ml-10 space-y-1">
                  <p class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ customerData.name || 'Chưa nhập' }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ customerData.tel || 'Chưa có SĐT' }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ customerData.street || 'Chưa có địa chỉ' }}
                    {{ customerData.ward ? '- ' + getWardLabel(customerData.ward) : '' }}
                  </p>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Món ăn -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                    <i class="pi pi-shopping-bag text-orange-600 dark:text-orange-400 text-xs"></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">Thực đơn</p>
                </div>
                <div class="ml-10">
                  <ul class="space-y-1">
                    <li v-for="d in dishData" :key="d.id" class="text-sm text-gray-600 dark:text-gray-400">
                      • {{ d.name }} - {{ d.dish_group_name }}
                    </li>
                    <li v-if="!dishData?.length" class="text-sm text-gray-400 italic">
                      Chưa chọn món
                    </li>
                  </ul>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Thời gian -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="pi pi-calendar text-blue-600 dark:text-blue-400 text-xs"></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">Thời gian</p>
                </div>
                <div class="ml-10 space-y-1">
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    • Lịch âm: Ngày {{ schedule?.lunarDay || '?' }} tháng {{ schedule?.lunarMonth || '?' }} năm {{ schedule?.lunarCanChi || '?' }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    • Lịch dương: {{ schedule.solarDate || 'Chưa chọn' }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    • Buổi: {{ schedule?.session || 'Chưa chọn' }}
                  </p>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Số bàn & giá -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="pi pi-table text-purple-600 dark:text-purple-400 text-xs"></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">Số bàn & Giá</p>
                </div>
                <div class="ml-10 space-y-1">
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Số bàn: <span class="font-medium text-gray-900 dark:text-white">{{ soBanGia.soBan || '0' }} bàn</span>
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Đơn giá: <span class="font-medium text-gray-900 dark:text-white">{{ (soBanGia.donGia || 0).toLocaleString() }}đ / bàn</span>
                  </p>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Ghi chú -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                    <i class="pi pi-comment text-yellow-600 dark:text-yellow-400 text-xs"></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">Ghi chú</p>
                </div>
                <div class="ml-10">
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ note || 'Không có ghi chú' }}
                  </p>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Voucher -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 rounded-lg bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center">
                    <i class="pi pi-ticket text-pink-600 dark:text-pink-400 text-xs"></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">Voucher</p>
                </div>
                <div class="ml-10">
                  <ul class="space-y-1">
                    <li v-for="v in voucherData" :key="v.id" class="text-sm text-gray-600 dark:text-gray-400">
                      • {{ v.code }}
                    </li>
                    <li v-if="!voucherData?.length" class="text-sm text-gray-400 italic">
                      Không áp dụng
                    </li>
                  </ul>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Nhân viên -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="pi pi-users text-green-600 dark:text-green-400 text-xs"></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">Nhân viên</p>
                </div>
                <div class="ml-10">
                  <ul class="space-y-1">
                    <li v-for="s in staffData" :key="s.id" class="text-sm text-gray-600 dark:text-gray-400">
                      • {{ s.name }}
                    </li>
                    <li v-if="!staffData?.length" class="text-sm text-gray-400 italic">
                      Chưa chọn nhân viên
                    </li>
                  </ul>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Tổng tiền -->
              <div class="p-4 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl">
                <div class="flex justify-between items-center">
                  <span class="text-base font-semibold text-gray-700 dark:text-gray-300">Tổng tiền</span>
                  <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                    {{ tongTien.toLocaleString() }} đ
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>