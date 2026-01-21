<script setup>
import { onMounted, ref, computed } from "vue";
import { getOrderById, updateOrder } from "../../../services/orderService";
import { useRoute, useRouter } from "vue-router";
import { useToast } from "primevue/usetoast";
import CustomerForm from "@/components/order/CustomerForm.vue";
import { getAllWards } from "../../../services/userService";
import ServiceForm from "@/components/order/ServiceForm.vue";
import { getDish } from "../../../services/dishService";
import TableAndPriceForm from "@/components/order/TableAndPriceForm.vue";
import NoteForm from "@/components/order/NoteForm.vue";
import VoucherForm from "@/components/order/VoucherForm.vue";
import StaffForm from "@/components/order/StaffForm.vue";
import { getAllStaff } from "../../../services/staffService";
import { getAllVouchers } from "../../../services/voucherService";
import ScheduleAtForm from "@/components/order/ScheduleAtForm.vue";

const route = useRoute();
const router = useRouter();
const loading = ref(false);
const toast = useToast();

const wards = ref([]);
const dish = ref([]);
const staff = ref([]);
const voucher = ref([]);

const OrderData = ref({
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
    isLeapMonth: null,
    lunarYear: "",
    lunarCanChi: "",
    session: "",
  },
  soBanGia: {
    soBan: 0,
    donGia: 0,
    tienCoc: 0,
  },
  dish: [],
  staff: [],
  voucher: [],
  note: "",
});

// Computed properties
const tongTien = computed(() => {
  const soBan = Number(OrderData.value.soBanGia.soBan) || 0;
  const donGia = Number(OrderData.value.soBanGia.donGia) || 0;
  return soBan * donGia;
});

const getWardLabel = (wardId) => {
  if (!wardId) return null;
  const found = wards.value.find((w) => w.id === wardId);
  return found ? found.label : null;
};

onMounted(async () => {
  await Promise.all([loadStaff(), loadWard(), loadDish()]);
  await loadVoucher();
  await loadOrder();
});

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

const loadOrder = async () => {
  loading.value = true;
  try {
    const resp = await getOrderById(route.params.id);
    const data = resp.data.data;

    OrderData.value.customer = data.customer;
    OrderData.value.dish = dish.value.filter(d =>
      data.dishes.some(selected => selected.id === d.id)
    );
    OrderData.value.soBanGia = {
      soBan: data.table_count,
      donGia: data.price_per_table,
      tienCoc: data.deposit_amount,
    };
    OrderData.value.schedule = {
      lunarDay: data.schedule?.lunar_day ?? null,
      lunarMonth: data.schedule?.lunar_month ?? null,
      lunarYear: data.schedule?.lunar_year ?? null,
      lunarCanChi: data.lunarCanChi,
      isLeapMonth: data.schedule?.isLeapMonth,
      session: data.schedule.session,
      solarDate: data.event_date,
    };
    OrderData.value.note = data.note;
    OrderData.value.staff = staff.value.filter((s) =>
      data.staffs.some((o) => o.id === s.id)
    );
    OrderData.value.voucher = data.vouchers
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Cập nhật món ăn thất bại",
      life: 3000,
    });
  } finally {
    loading.value = false;
  }
};

const handleSubmit = async () => {
  loading.value = true;
  try {
    const payload = {
      customer: {
        ...OrderData.value.customer,
        ward: Number(OrderData.value.customer.ward),
      },

      schedule: OrderData.value.schedule,
      soBanGia: OrderData.value.soBanGia,
      note: OrderData.value.note?.trim() || undefined,

      dishData: OrderData.value.dish.length
        ? OrderData.value.dish.map((d) => ({ id: Number(d.id) }))
        : undefined,

      voucherData: OrderData.value.voucher.length
        ? OrderData.value.voucher.map(v => ({ id: v.id }))
        : undefined,


      staffData: OrderData.value.staff.length
        ? OrderData.value.staff.map((s) => ({ id: Number(s.id) }))
        : undefined,
    };
    await updateOrder(route.params.id, payload);
    toast.add({
      severity: "success",
      summary: "Thành công",
      detail: "Cập nhật đơn hàng thành công",
      life: 3000,
    });
    router.push("/orders");
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Lỗi",
      detail: "Không cập nhật được đơn hàng",
      life: 3000,
    });
  } finally {
    loading.value = false;
  }
};
const formatVND = (value) => {
  const number = Number(value) || 0;
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
    minimumFractionDigits: 0,
  }).format(number);
};

const sessionLabel = computed(() => {
  const session = OrderData.value.schedule.session;

  const map = {
    trua: "trưa",
    chieu: "chiều",
  };
  return map[session] || "Chưa chọn";
});
</script>
<template>
  <div
    class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900 p-6"
  >
    <div class="max-w-7xl mx-auto">
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
                Cập nhật đơn hàng
              </h1>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Kiểm tra thông tin đơn hàng vào hệ thống
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form bên trái -->
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

            <form class="p-8" @submit.prevent="handleSubmit">
              <div class="space-y-8">
                <CustomerForm v-model="OrderData.customer" :wards="wards" />
                <ServiceForm v-model="OrderData.dish" :dish="dish" />
                <ScheduleAtForm v-model="OrderData.schedule" />
                <TableAndPriceForm v-model="OrderData.soBanGia" />
                <NoteForm v-model="OrderData.note" />
                <VoucherForm
                  v-model="OrderData.voucher"
                  :voucher="voucher"
                  :soBan="OrderData.soBanGia.soBan"
                />
                <StaffForm v-model="OrderData.staff" :staff="staff" />

                <div
                  class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700"
                >
                  <button
                    type="button"
                    @click="router.push('/orders')"
                    class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-all shadow-sm hover:shadow-md"
                  >
                    <i class="pi pi-times mr-2"></i> Huỷ
                  </button>
                  <button
                    type="submit"
                    :loading="loading"
                    class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl hover:opacity-90 transition-all shadow-lg hover:shadow-xl disabled:opacity-50"
                  >
                    <i class="pi pi-check mr-2"></i>
                    {{ loading ? "Đang cập nhật..." : "Cập nhật" }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Preview Sidebar - Giống Create -->
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
                    {{ OrderData.customer.name || "Chưa nhập" }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ OrderData.customer.tel || "Chưa có SĐT" }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ OrderData.customer.street || "Chưa có địa chỉ" }}
                    {{
                      OrderData.customer.ward
                        ? "- " + getWardLabel(OrderData.customer.ward)
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
                      v-for="d in OrderData.dish"
                      :key="d.id"
                      class="text-sm text-gray-600 dark:text-gray-400"
                    >
                      • {{ d.name }} - {{ d.dish_group_name }}
                    </li>
                    <li
                      v-if="!OrderData.dish.length"
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
                    {{ OrderData.schedule.lunarDay || "?" }} tháng
                    {{ OrderData.schedule.lunarMonth || "?" }} năm
                    {{ OrderData.schedule.lunarCanChi || "?" }}
                    ({{ OrderData.schedule.lunarYear || "?" }})
                  </p>
                  <p
                    v-if="OrderData.schedule.isLeapMonth"
                    class="text-sm text-gray-600 dark:text-gray-400"
                  >
                    • Tháng nhuận
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    • Lịch dương:
                    {{ OrderData.schedule.solarDate || "Chưa chọn" }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    • Buổi {{ sessionLabel || "Chưa chọn" }}
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
                      >{{ OrderData.soBanGia.soBan || "0" }} bàn</span
                    >
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Đơn giá:
                    <span class="font-medium text-gray-900 dark:text-white"
                      >{{ (OrderData.soBanGia.donGia || 0).toLocaleString() }}đ
                      / bàn</span
                    >
                  </p>
                </div>
              </div>

              <hr class="border-gray-200 dark:border-gray-700" />

              <!-- Địa điểm tổ chức -->
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
                    {{ OrderData.note || "Không có ghi chú" }}
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
                      v-for="v in OrderData.voucher"
                      :key="v.id"
                      class="text-sm text-gray-600 dark:text-gray-400"
                    >
                      • {{ v.name }}
                    </li>
                    <li
                      v-if="!OrderData.voucher.length"
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
                      v-for="s in OrderData.staff"
                      :key="s.id"
                      class="text-sm text-gray-600 dark:text-gray-400"
                    >
                      • {{ s.name }}
                    </li>
                    <li
                      v-if="!OrderData.staff.length"
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
                    {{ formatVND(tongTien) }}
                  </span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-base text-gray-600 dark:text-gray-400"
                    >Đã cọc</span
                  >
                  <span
                    class="text-lg font-semibold text-green-600 dark:text-green-400"
                  >
                    - {{ formatVND(OrderData.soBanGia.tienCoc) }}
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
                          formatVND(
                            Math.max(
                              tongTien - (OrderData.soBanGia.tienCoc || 0),
                              0
                            )
                          )
                        }}
                        đ
                      </p>
                    </div>
                  </div>
                </div>
                <div
                  v-if="OrderData.soBanGia.soBan && OrderData.soBanGia.donGia"
                  class="text-sm text-gray-500 dark:text-gray-400 text-right mt-2"
                >
                  {{ OrderData.soBanGia.soBan }} bàn ×
                  {{ formatVND(OrderData.soBanGia.donGia) }}/bàn
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>