<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "primevue/usetoast";

// Components
import CustomerForm from "@/components/order/CustomerForm.vue";
import ServiceForm from "@/components/order/ServiceForm.vue";
import ScheduleAtForm from "@/components/order/ScheduleAtForm.vue";
import TableAndPriceForm from "@/components/order/TableAndPriceForm.vue";
import NoteForm from "@/components/order/NoteForm.vue";
import VoucherForm from "@/components/order/VoucherForm.vue";
import StaffForm from "@/components/order/StaffForm.vue";

import { getAllWards } from "../../../services/userService";
import { getAllStaff } from "../../../services/staffService";
import { getAllVouchers } from "../../../services/voucherService";
import { getDish } from "../../../services/dishService";
import { createOrder } from "../../../services/orderService";

const loading = ref(false);
const toast = useToast();
const router = useRouter();

const wards = ref([]);
const staff = ref([]);
const voucher = ref([]);
const dish = ref([]);

const orderData = ref({
  customer: {
    name: "",
    tel: "",
    street: "",
    ward: null,
  },
  schedule: {
    solarDate: null,
    lunarDay: null,
    lunarMonth: null,
    lunarYear: "",
    lunarCanChi:"",
    session: "",
  },
  soBanGia: {
    soBan: "",
    donGia: "",
    tienCoc: 0,
  },
  dishData: [],
  note: "",
  voucherData: [],
  staffData: [],
});

const tongTien = computed(() => {
  const soBan = Number(orderData.value.soBanGia.soBan) || 0;
  const donGia = Number(orderData.value.soBanGia.donGia) || 0;
  return soBan * donGia;
});

const canShowVoucher = computed(() => {
  const soBan = Number(orderData.value.soBanGia.soBan) || 0;
  return soBan >= 50;
});

const getWardLabel = (wardId) => {
  if (!wardId) return null;
  const found = wards.value.find((w) => w.id === wardId);
  return found ? found.label : null;
};

const formatDate = (date) => {
  if (!date) return null
  const d = new Date(date)
  if (isNaN(d.getTime())) {
    console.error('INVALID DATE:', date)
    return null
  }

  return d.toISOString().slice(0, 10)
}

onMounted(async () => {
  await Promise.all(
    [
      loadWard(), 
      loadStaff(), 
      loadVoucher(), 
      loadDish()
    ]);
});

const loadWard = async () => {
  try {
    const resp = await getAllWards();
    wards.value = resp.data.data.wards;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Tải danh sách phường thất bại",
      life: 3000,
    });
  }
};

const loadStaff = async () => {
  try {
    const resp = await getAllStaff();
    staff.value = resp.data.data.data;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Tải danh sách nhân viên thất bại",
      life: 3000,
    });
  }
};

const loadVoucher = async () => {
  try {
    const resp = await getAllVouchers();
    voucher.value = resp.data.data.items;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Tải voucher thất bại",
      life: 3000,
    });
  }
};

const loadDish = async () => {
  try {
    const resp = await getDish();
    dish.value = resp.data.data;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Tải món ăn thất bại",
      life: 3000,
    });
  }
};

// Reset form
const resetForm = () => {
  orderData.value = {
    customer: { name: "", tel: "", street: "", ward: null },
    schedule: {
      solarDate: "",
      lunarDay: null,
      lunarMonth: null,
      lunarYear: "",
      session: "",
    },
    soBanGia: { soBan: "", donGia: "", tienCoc: 0 },
    dishData: [],
    note: "",
    voucherData: [],
    staffData: [],
  };
  toast.add({
    severity: "info",
    summary: "Đã làm mới",
    detail: "Form đã được reset về mặc định",
    life: 3000,
  });
};

const submitOrder = async () => {
  loading.value = true
  try {
    const payload = {
      ...orderData.value,
      schedule: {
        ...orderData.value.schedule,
        solarDate: formatDate(orderData.value.schedule.solarDate),
      },
      dishData: orderData.value.dishData.map(d => ({
        id: Number(d.id)
      })),
      voucherData: orderData.value.voucherData
      .filter(v => v)
      .map(v => ({
        id: typeof v === 'object' ? String(v.id) : String(v)
      })),

      staffData: orderData.value.staffData.map(s => ({
        id: Number(s.id),
        session: s.session
      })),
    }
    await createOrder(payload)
    toast.add({
      severity: "success",
      summary: "Thành công",
      detail: "Đã tạo đơn hàng thành công",
      life: 3000,
    });
    router.push('/orders')
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Lỗi',
      detail: error.response?.data?.message || 'Có lỗi xảy ra',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div
    class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900 p-6"
  >
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 mb-6"
      >
        <div class="flex items-center gap-4">
          <button
            @click="router.push('/orders')"
            class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:scale-105 flex items-center justify-center shadow-lg transition-all"
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
          <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden"
          >
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
              <div class="flex items-center gap-3">
                <div
                  class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center"
                >
                  <i
                    class="pi pi-file-edit text-indigo-600 dark:text-indigo-400"
                  ></i>
                </div>
                <div>
                  <h2
                    class="text-lg font-semibold text-gray-900 dark:text-white"
                  >
                    Chi tiết đơn hàng
                  </h2>
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    Điền đầy đủ thông tin bên dưới
                  </p>
                </div>
              </div>
            </div>

            <form class="p-8" @submit.prevent="submitOrder">
              <div class="space-y-8">
                <CustomerForm v-model="orderData.customer" :wards="wards" />
                <ServiceForm :dish="dish" v-model="orderData.dishData" />
                <ScheduleAtForm v-model="orderData.schedule" />
                <TableAndPriceForm v-model="orderData.soBanGia" />
                <NoteForm v-model="orderData.note" />
                <VoucherForm
                  v-if="canShowVoucher"
                  :voucher="voucher"
                  v-model="orderData.voucherData"
                  :soBan="orderData.soBanGia.soBan"
                />
                <StaffForm :staff="staff" v-model="orderData.staffData" />

                <!-- Action Buttons -->
                <div
                  class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700"
                >
                  <button
                    type="button"
                    @click="resetForm"
                    class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-all shadow-sm hover:shadow-md"
                  >
                    <i class="pi pi-refresh mr-2"></i> Làm mới
                  </button>
                  <button
                    type="submit"
                    :loading="loading"
                    class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl hover:opacity-90 transition-all shadow-lg hover:shadow-xl disabled:opacity-50"
                  >
                    <i class="pi pi-check mr-2"></i>
                    {{ loading ? "Đang tạo..." : "Tạo đơn hàng" }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Preview Sidebar -->
        <div class="lg:col-span-1 space-y-6">
          <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-6"
          >
            <div
              class="p-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-blue-200 dark:border-blue-800"
            >
              <div class="text-center">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                  Dịch vụ tiệc cưới
                </h2>
                <h3
                  class="text-xl font-bold text-blue-800 dark:text-blue-400 mt-1"
                >
                  THANH AN HỘI
                </h3>
              </div>
            </div>

            <div class="p-6 space-y-4">
              <!-- Khách hàng -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div
                    class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center"
                  >
                    <i
                      class="pi pi-user text-emerald-600 dark:text-emerald-400 text-xs"
                    ></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">
                    Khách hàng
                  </p>
                </div>
                <div class="ml-10 space-y-1">
                  <p class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ orderData.customer.name || "Chưa nhập" }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ orderData.customer.tel || "Chưa có SĐT" }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ orderData.customer.street || "Chưa có địa chỉ" }}
                    {{
                      orderData.customer.ward
                        ? "- " + getWardLabel(orderData.customer.ward)
                        : ""
                    }}
                  </p>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Thực đơn -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div
                    class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center"
                  >
                    <i
                      class="pi pi-shopping-bag text-orange-600 dark:text-orange-400 text-xs"
                    ></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">
                    Thực đơn
                  </p>
                </div>
                <div class="ml-10">
                  <ul class="space-y-1">
                    <li
                      v-for="d in orderData.dishData"
                      :key="d.id"
                      class="text-sm text-gray-600 dark:text-gray-400"
                    >
                      • {{ d.name }} - {{ d.dish_group_name }}
                    </li>
                    <li
                      v-if="!orderData.dishData.length"
                      class="text-sm text-gray-400 italic"
                    >
                      Chưa chọn món
                    </li>
                  </ul>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Thời gian -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div
                    class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center"
                  >
                    <i
                      class="pi pi-calendar text-blue-600 dark:text-blue-400 text-xs"
                    ></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">
                    Thời gian
                  </p>
                </div>
                <div class="ml-10 space-y-1">
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    • Lịch âm: Ngày
                    {{ orderData.schedule.lunarDay || "?" }} tháng
                    {{ orderData.schedule.lunarMonth || "?" }} năm
                    {{ orderData.schedule.lunarCanChi || "?" }}
                    ({{ orderData.schedule.lunarYear || "?" }})
                    
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    • Lịch dương:
                    {{ orderData.schedule.solarDate || "Chưa chọn" }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    • Buổi: {{ orderData.schedule.session || "Chưa chọn" }}
                  </p>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Số bàn & Giá -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div
                    class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center"
                  >
                    <i
                      class="pi pi-table text-purple-600 dark:text-purple-400 text-xs"
                    ></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">
                    Số bàn & Giá
                  </p>
                </div>
                <div class="ml-10 space-y-1">
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Số bàn:
                    <span class="font-medium text-gray-900 dark:text-white"
                      >{{ orderData.soBanGia.soBan || "0" }} bàn</span
                    >
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Đơn giá:
                    <span class="font-medium text-gray-900 dark:text-white"
                      >{{ (orderData.soBanGia.donGia || 0).toLocaleString() }}đ
                      / bàn</span
                    >
                  </p>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Ghi chú -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div
                    class="w-8 h-8 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center"
                  >
                    <i
                      class="pi pi-comment text-yellow-600 dark:text-yellow-400 text-xs"
                    ></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">
                    Địa điểm tổ chức
                  </p>
                </div>
                <div class="ml-10">
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ orderData.note || "Không có ghi chú" }}
                  </p>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Voucher -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div
                    class="w-8 h-8 rounded-lg bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center"
                  >
                    <i
                      class="pi pi-ticket text-pink-600 dark:text-pink-400 text-xs"
                    ></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">
                    Voucher
                  </p>
                </div>
                <div class="ml-10">
                  <ul class="space-y-1">
                    <li
                      v-for="v in orderData.voucherData"
                      :key="v.id"
                      class="text-sm text-gray-600 dark:text-gray-400"
                    >
                      • {{ v.code }}
                    </li>
                    <li
                      v-if="!orderData.voucherData.length"
                      class="text-sm text-gray-400 italic"
                    >
                      Không áp dụng
                    </li>
                  </ul>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Nhân viên -->
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <div
                    class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center"
                  >
                    <i
                      class="pi pi-users text-green-600 dark:text-green-400 text-xs"
                    ></i>
                  </div>
                  <p class="font-semibold text-gray-700 dark:text-gray-300">
                    Nhân viên
                  </p>
                </div>
                <div class="ml-10">
                  <ul class="space-y-1">
                    <li
                      v-for="s in orderData.staffData"
                      :key="s.id"
                      class="text-sm text-gray-600 dark:text-gray-400"
                    >
                      • {{ s.name }}
                    </li>
                    <li
                      v-if="!orderData.staffData.length"
                      class="text-sm text-gray-400 italic"
                    >
                      Chưa chọn nhân viên
                    </li>
                  </ul>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Tổng kết thanh toán -->
              <div
                class="p-6 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl space-y-4"
              >
                <div class="flex justify-between items-center">
                  <span
                    class="text-base font-medium text-gray-700 dark:text-gray-300"
                    >Tổng tiền</span
                  >
                  <span
                    class="text-xl font-semibold text-gray-900 dark:text-white"
                  >
                    {{ tongTien.toLocaleString() }} đ
                  </span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-base text-gray-600 dark:text-gray-400"
                    >Đã cọc</span
                  >
                  <span
                    class="text-lg font-semibold text-green-600 dark:text-green-400"
                  >
                    - {{ (orderData.soBanGia.tienCoc || 0).toLocaleString() }} đ
                  </span>
                </div>
                <hr
                  class="border-t-2 border-dashed border-indigo-300 dark:border-indigo-700"
                />
                <div class="flex justify-between items-center pt-3">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg"
                    >
                      <i class="pi pi-wallet text-white text-xl"></i>
                    </div>
                    <div>
                      <p class="text-sm text-gray-600 dark:text-gray-400">
                        Còn phải thanh toán
                      </p>
                      <p
                        class="text-3xl font-bold text-blue-600 dark:text-blue-400"
                      >
                        {{
                          Math.max(
                            tongTien - (orderData.soBanGia.tienCoc || 0),
                            0
                          ).toLocaleString()
                        }}
                        đ
                      </p>
                    </div>
                  </div>
                </div>
                <div
                  v-if="orderData.soBanGia.soBan && orderData.soBanGia.donGia"
                  class="text-sm text-gray-500 dark:text-gray-400 text-right mt-2"
                >
                  {{ orderData.soBanGia.soBan }} bàn ×
                  {{ (orderData.soBanGia.donGia || 0).toLocaleString() }} đ/bàn
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>