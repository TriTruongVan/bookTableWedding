<script setup lang="ts">
import Card from "primevue/card";
import Button from "primevue/button";
import Tag from "primevue/tag";
import { Icon } from "@iconify/vue";
import { getUser, updateUser, updatePass } from "../../services/userService";
import { onMounted, ref } from "vue";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import { useToast } from "primevue/usetoast";
import { useRouter } from "vue-router";
import Password from "primevue/password";

const user = ref({
  name: '',
  tel: '',
});
const pass_word = ref({
  password: '',
  confirmPassword: '',
})
const showEdit = ref(false);
const showPassword = ref(false);
const loading = ref(false)
const toast = useToast()
const router = useRouter()

const openEdit = () => {
  showEdit.value = true;
};

const closeEdit = () => {
  showEdit.value = false;
};

const openPass = () => {
  showPassword.value = true;
};

const closePass = () => {
  showPassword.value = false;
  pass_word.value = {
    password: '',
    confirmPassword: '',
  }
};

onMounted(async () => {
  await loadUser();
});

const formatDate = (dateString:any) => {
  return new Date(dateString).toLocaleDateString("vi-VN");
};

const loadUser = async () => {
  const resp = await getUser();
  user.value = resp.data.data;
};

const saveUser = async () =>{
  loading.value = true
  try {
    await updateUser(user.value)
    toast.add({
      severity: 'success',
      summary: 'Thành công',
      detail: 'Cập nhật Admin thành công',
      life: 3000
    })
    closeEdit()
  } catch (error:any) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Cập nhật Admin thất bại',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}

const savePass = async () => {
  loading.value = true
  try {
    await updatePass(pass_word.value)
    toast.add({
      severity: 'success',
      summary: 'Thành công',
      detail: 'Cập nhật mật khẩu thành công',
      life: 3000
    })
    closePass()
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Lỗi',
      detail: 'Cập nhật mật khẩu thất bại',
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
    <div class="max-w-5xl mx-auto">
      <!-- Header Section -->
      <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 mb-6"
      >
        <div class="flex justify-between items-center">
          <div class="space-y-2">
            <div class="flex items-center gap-3">
              <div
                class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg"
              >
                <i class="pi pi-user text-white text-xl"></i>
              </div>
              <div>
                <h1
                  class="font-bold text-3xl bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent"
                >
                  Hồ sơ cá nhân
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  Quản lý thông tin tài khoản của bạn
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Profile Information Card -->
      <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden"
      >
        <!-- Card Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center"
              >
                <i
                  class="pi pi-id-card text-indigo-600 dark:text-indigo-400"
                ></i>
              </div>
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                  Thông tin tài khoản
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Chi tiết thông tin cá nhân
                </p>
              </div>
            </div>
            <div class="flex gap-2">
              <Button
                icon="pi pi-pencil"
                label="Chỉnh sửa"
                outlined
                size="small"
                class="!rounded-lg"
                @click="openEdit"
              />
              <Button
                icon="pi pi-key"
                label="Đổi mật khẩu"
                outlined
                size="small"
                class="!rounded-lg"
                @click="openPass"
              />
            </div>
          </div>
        </div>

        <!-- Card Content -->
        <div class="p-8">
          <div class="space-y-6">
            <!-- Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Name Field -->
              <div
                class="bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-800/50 dark:to-slate-800/50 rounded-xl p-5 border border-gray-200 dark:border-gray-700"
              >
                <div class="flex items-center gap-3 mb-3">
                  <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center shadow-md"
                  >
                    <i class="pi pi-user text-white text-sm"></i>
                  </div>
                  <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                    Họ và tên
                  </h4>
                </div>
                <p
                  class="text-base text-gray-700 dark:text-gray-300 ml-13 font-medium"
                >
                  {{ user.name }}
                </p>
              </div>

              <!-- Phone Field -->
              <div
                class="bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-800/50 dark:to-slate-800/50 rounded-xl p-5 border border-gray-200 dark:border-gray-700"
              >
                <div class="flex items-center gap-3 mb-3">
                  <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center shadow-md"
                  >
                    <i class="pi pi-phone text-white text-sm"></i>
                  </div>
                  <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                    Số điện thoại
                  </h4>
                </div>
                <p
                  class="text-base text-gray-700 dark:text-gray-300 ml-13 font-medium font-mono"
                >
                  {{ user.tel }}
                </p>
              </div>
            </div>

            <!-- Row 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Password Field -->
              <div
                class="bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-800/50 dark:to-slate-800/50 rounded-xl p-5 border border-gray-200 dark:border-gray-700"
              >
                <div class="flex items-center gap-3 mb-3">
                  <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center shadow-md"
                  >
                    <i class="pi pi-lock text-white text-sm"></i>
                  </div>
                  <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                    Mật khẩu
                  </h4>
                </div>
                <p
                  class="text-base text-gray-700 dark:text-gray-300 ml-13 font-medium font-mono"
                >
                  **********
                </p>
              </div>

              <!-- Role Field -->
              <div
                class="bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-800/50 dark:to-slate-800/50 rounded-xl p-5 border border-gray-200 dark:border-gray-700"
              >
                <div class="flex items-center gap-3 mb-3">
                  <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-400 to-rose-500 flex items-center justify-center shadow-md"
                  >
                    <i class="pi pi-shield text-white text-sm"></i>
                  </div>
                  <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                    Vai trò
                  </h4>
                </div>
                <div class="ml-13">
                  <Tag
                    value="Admin"
                    severity="danger"
                    class="!px-4 !py-2 !rounded-lg !font-medium"
                  >
                    <template #default>
                      <div class="flex items-center gap-2">
                        <i class="pi pi-star-fill text-xs"></i>
                        <span>Admin</span>
                      </div>
                    </template>
                  </Tag>
                </div>
              </div>
            </div>

            <!-- Row 3 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Status Field -->
              <div
                class="bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-800/50 dark:to-slate-800/50 rounded-xl p-5 border border-gray-200 dark:border-gray-700"
              >
                <div class="flex items-center gap-3 mb-3">
                  <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-md"
                  >
                    <i class="pi pi-check-circle text-white text-sm"></i>
                  </div>
                  <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                    Trạng thái
                  </h4>
                </div>
                <div class="ml-13">
                  <Tag
                    value="Hoạt động"
                    severity="success"
                    class="!px-4 !py-2 !rounded-lg !font-medium"
                  >
                    <template #default>
                      <div class="flex items-center gap-2">
                        <i class="pi pi-circle-fill text-xs"></i>
                        <span>Hoạt động</span>
                      </div>
                    </template>
                  </Tag>
                </div>
              </div>

              <!-- Created Date Field -->
              <div
                class="bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-800/50 dark:to-slate-800/50 rounded-xl p-5 border border-gray-200 dark:border-gray-700"
              >
                <div class="flex items-center gap-3 mb-3">
                  <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center shadow-md"
                  >
                    <i class="pi pi-calendar text-white text-sm"></i>
                  </div>
                  <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                    Ngày tạo
                  </h4>
                </div>
                <p
                  class="text-base text-gray-700 dark:text-gray-300 ml-13 font-medium"
                >
                  {{ formatDate(user.createdAt) }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Card Footer -->
        <div
          class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50"
        >
          <div
            class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400"
          >
            <div
              class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center"
            >
              <i
                class="pi pi-info-circle text-indigo-600 dark:text-indigo-400 text-sm"
              ></i>
            </div>
            <span>
              Cập nhật thông tin tài khoản của bạn để giữ liên lạc và bảo mật
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Profile Dialog -->
    <Dialog
      v-model:visible="showEdit"
      modal
      :draggable="false"
      class="w-[550px]"
    >
      <template #header>
        <div class="flex items-center gap-3">
          <div
            class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-md"
          >
            <i class="pi pi-pencil text-white text-sm"></i>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              Chỉnh sửa thông tin
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
              Cập nhật thông tin cá nhân của bạn
            </p>
          </div>
        </div>
      </template>

      <div class="space-y-5 py-2">
        <div>
          <label
            class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2"
          >
            <div
              class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center"
            >
              <i class="pi pi-user text-blue-600 dark:text-blue-400 text-xs"></i>
            </div>
            Họ và tên
          </label>
          <InputText
            v-model="user.name"
            class="w-full !rounded-lg !border-2 focus:!border-indigo-500 dark:focus:!border-indigo-400 !py-3"
            placeholder="Nhập họ và tên"
          />
        </div>
        <div>
          <label
            class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2"
          >
            <div
              class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center"
            >
              <i
                class="pi pi-phone text-green-600 dark:text-green-400 text-xs"
              ></i>
            </div>
            Số điện thoại
          </label>
          <InputText
            v-model="user.tel"
            class="w-full !rounded-lg !border-2 focus:!border-indigo-500 dark:focus:!border-indigo-400 !py-3 font-mono"
            placeholder="Nhập số điện thoại"
          />
        </div>
      </div>

      <template #footer>
        <div
          class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700"
        >
          <Button
            label="Huỷ"
            severity="secondary"
            outlined
            @click="closeEdit"
            class="!rounded-lg !px-6 !py-2.5"
            icon="pi pi-times"
          />
          <Button
            label="Lưu thay đổi"
            @click="saveUser"
            class="!rounded-lg !px-6 !py-2.5 !bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500"
            icon="pi pi-check"
          />
        </div>
      </template>
    </Dialog>

    <!-- Change Password Dialog -->
    <Dialog
      v-model:visible="showPassword"
      modal
      :draggable="false"
      class="w-[550px]"
    >
      <template #header>
        <div class="flex items-center gap-3">
          <div
            class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 via-pink-500 to-rose-500 flex items-center justify-center shadow-md"
          >
            <i class="pi pi-key text-white text-sm"></i>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              Thay đổi mật khẩu
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
              Cập nhật mật khẩu để bảo mật tài khoản
            </p>
          </div>
        </div>
      </template>

      <div class="space-y-5 py-2">
        <div>
          <label
            class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2"
          >
            <div
              class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center"
            >
              <i
                class="pi pi-lock text-purple-600 dark:text-purple-400 text-xs"
              ></i>
            </div>
            Mật khẩu mới
          </label>
          <Password
            v-model="pass_word.password"
            toggleMask
            :feedback="false"
            placeholder="Nhập mật khẩu mới"
            inputClass="w-full !rounded-lg !border-2 focus:!border-indigo-500 dark:focus:!border-indigo-400 !py-3"
            class="w-full"
            :pt="{
              root: { class: 'w-full' },
              input: { class: 'w-full !rounded-lg !border-2 focus:!border-purple-500 dark:focus:!border-purple-400 !py-3 !pr-12' }
            }"
          />
        </div>

        <div>
          <label
            class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2"
          >
            <div
              class="w-8 h-8 rounded-lg bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center"
            >
              <i
                class="pi pi-check-circle text-pink-600 dark:text-pink-400 text-xs"
              ></i>
            </div>
            Xác nhận mật khẩu mới
          </label>
          <Password
            v-model="pass_word.confirmPassword"
            toggleMask
            :feedback="false"
            placeholder="Nhập lại mật khẩu mới"
            inputClass="w-full !rounded-lg !border-2 focus:!border-pink-500 dark:focus:!border-pink-400 !py-3"
            class="w-full"
            :pt="{
              root: { class: 'w-full' },
              input: { class: 'w-full !rounded-lg !border-2 focus:!border-pink-500 dark:focus:!border-pink-400 !py-3 !pr-12' }
            }"
          />
        </div>

        <div
          class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4"
        >
          <div class="flex gap-3">
            <div
              class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0"
            >
              <i
                class="pi pi-info-circle text-amber-600 dark:text-amber-400 text-sm"
              ></i>
            </div>
            <div class="text-sm text-amber-800 dark:text-amber-200">
              <p class="font-semibold mb-1">Lưu ý về mật khẩu:</p>
              <ul class="space-y-1 text-xs">
                <li>• Mật khẩu phải có ít nhất 8 ký tự</li>
                <li>• Nên bao gồm chữ hoa, chữ thường và số</li>
                <li>• Không sử dụng mật khẩu quá đơn giản</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <template #footer>
        <div
          class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700"
        >
          <Button
            label="Huỷ"
            severity="secondary"
            outlined
            @click="closePass"
            class="!rounded-lg !px-6 !py-2.5"
            icon="pi pi-times"
          />
          <Button
            label="Đổi mật khẩu"
            @click="savePass"
            :loading="loading"
            class="!rounded-lg !px-6 !py-2.5 !bg-gradient-to-br from-purple-500 via-pink-500 to-rose-500"
            icon="pi pi-check"
          />
        </div>
      </template>
    </Dialog>
  </div>
</template>
