<script setup>
import Button from 'primevue/button';
import { useRouter } from 'vue-router';


const router = useRouter()
</script>
<template>
  <div>
    <div class="flex items-center mb-6 px-4 pt-6">
      <Button
        icon="pi pi-arrow-left"
        text
        class="mr-4"
        @click="router.push('/customer')"
      />
      <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
          Tạo khách hàng
        </h1>
      </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Customer Form -->
      <div class="lg:col-span-2">
        <Card class="bg-white dark:bg-gray-800">
          <template #content>
            <form
              class="space-y-8"
              @submit.prevent="handleSubmit"
            >
              <CreateCustomerForm
                v-model="customer"
              />
              <!-- Action Buttons -->
              <div
                class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600"
              >
                <Button
                  type="submit"
                  :loading="loadingBtn"
                  icon="pi pi-user-plus"
                  :label="t('CreateCustomer')"
                  class="bg-blue-600 hover:bg-blue-700"
                />
              </div>
            </form>
          </template>
        </Card>
      </div>
    </div>
  </div>
</template>
