<script setup lang="ts">
import { useToast } from "primevue/usetoast";
import { computed, onMounted, ref } from "vue";
import { updateStatus } from "../../../services/orderService";
import { useRouter } from "vue-router";

const loading = ref(false)
const toast = useToast()
const router = useRouter()

const props = defineProps({
  visible: Boolean,
  order: Object,
});

const emit = defineEmits(["close","update"]);

const formatCurrency = (value: any) => {
  return Number(value || 0).toLocaleString("vi-VN");
};

const qrLink = computed(() => {
  if (!props.order) return "";
  const bank = "TECHCOMBANK";
  const account = "19037877475011";
  const name = "TRAN THI THAO VY";
  const amount = props.order?.total_amount || 0;
  const content = `Dịch vụ nấu ăn THANH AN HỘI ${props.order?.id}`;
  return `https://img.vietqr.io/image/${bank}-${account}-compact2.png?amount=${amount}&addInfo=${encodeURIComponent(content)}&accountName=${encodeURIComponent(name)}`;
});

const confirmPay = async () => {
  loading.value = true
  try {
    await updateStatus(props.order?.id)
    toast.add({
      severity: 'success',
      summary: "Thành công",
      detail:"Đã xác nhận thanh toán",
      life: 3000,
    })
    emit('update')
    emit('close')
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: "Lỗi",
      detail:"Xác nhận thất bại",
      life: 3000,
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <Transition name="modal-fade">
    <div v-if="visible" class="modal-overlay" @click.self="$emit('close')">
      <div class="modal-container">

        <!-- Header -->
        <div class="modal-header-bar">
          <div class="ornament">✦</div>
          <span class="header-title">Thanh Toán</span>
          <div class="ornament">✦</div>
        </div>

        <!-- Close -->
        <button class="close-btn" @click="emit('close')" aria-label="Đóng">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>

        <div class="modal-body">

          <div class="divider-ornament">
            <span class="line"></span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L22 12L12 22L2 12Z"/></svg>
            <span class="line"></span>
          </div>

          <!-- Customer -->
          <div class="info-section">
            <p class="section-label">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              Thông Tin Khách Hàng
            </p>
            <div class="info-grid">
              <div class="info-row"><span class="info-icon">👤</span><span>{{ props.order?.customer?.name || '—' }}</span></div>
              <div class="info-row"><span class="info-icon">📞</span><span>{{ props.order?.customer?.tel || '—' }}</span></div>
              <div class="info-row"><span class="info-icon">📍</span><span>{{ props.order?.customer?.address || '—' }}</span></div>
            </div>
          </div>

          <!-- Dishes -->
          <div class="info-section">
            <p class="section-label">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20M21 15V2"/><path d="M18 2v4c0 1.1.9 2 2 2h1M21 15a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1h18v1z"/></svg>
              Danh Sách Món
            </p>
            <ul class="dish-list">
              <li v-for="dish in order?.dishes" :key="dish.id" class="dish-item">
                <span class="dish-dot">◆</span>
                <span>{{ dish.name }}</span>
                <span v-if="dish.price" class="dish-price">{{ formatCurrency(dish.price) }}đ</span>
              </li>
            </ul>
          </div>

          <!-- Total -->
          <div class="total-section">
            <span class="total-label">Tổng Thanh Toán</span>
            <span class="total-amount">{{ formatCurrency(order?.total_amount) }}<span class="currency">đ</span></span>
          </div>

          <!-- QR -->
          <div class="qr-section">
            <p class="section-label" style="justify-content:center;margin-bottom:16px;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h7v7M14 14v3M17 14h4"/></svg>
              Quét Mã QR Để Thanh Toán
            </p>
            <div class="qr-frame">
              <div class="qr-corner tl"></div><div class="qr-corner tr"></div>
              <div class="qr-corner bl"></div><div class="qr-corner br"></div>
              <img :src="qrLink" class="qr-image" alt="QR Code" />
            </div>
            <p class="qr-note">Mở ứng dụng ngân hàng và quét mã để thanh toán</p>
          </div>

        </div>

        <!-- Footer -->
        <div class="modal-footer gap-2">
          <button class="btn-close-main" @click="emit('close')"><span>Đóng</span></button>
          <button class="btn-close-main" @click="confirmPay()"><span>Xác nhận thanh toán</span></button>
        </div>

        <div class="petal petal-1">❀</div>
        <div class="petal petal-2">❀</div>
        <div class="petal petal-3">✿</div>
        <div class="petal petal-4">✿</div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=Jost:wght@300;400;500&display=swap');

.modal-overlay {
  position: fixed; inset: 0; z-index: 50;
  display: flex; align-items: center; justify-content: center;
  background: rgba(10,30,70,0.5); backdrop-filter: blur(7px); padding: 16px;
}

.modal-container {
  position: relative;
  background: #ffffff;
  border-radius: 8px;
  width: 100%; max-width: 560px; max-height: 92vh; overflow-y: auto;
  box-shadow: 0 28px 70px rgba(30,80,160,0.18), inset 0 0 0 1px rgba(59,130,246,0.2);
  font-family: 'Jost', sans-serif; color: #1E3A5F;
  background-image:
    radial-gradient(ellipse at 8% 0%, rgba(59,130,246,0.06) 0%, transparent 55%),
    radial-gradient(ellipse at 92% 100%, rgba(96,165,250,0.05) 0%, transparent 55%);
}
.modal-container::-webkit-scrollbar { width: 4px; }
.modal-container::-webkit-scrollbar-track { background: #EFF6FF; }
.modal-container::-webkit-scrollbar-thumb { background: #93C5FD; border-radius: 2px; }

.modal-header-bar {
  display: flex; align-items: center; justify-content: center; gap: 14px;
  padding: 20px 24px 16px;
  border-bottom: 1px solid rgba(59,130,246,0.2);
  background: linear-gradient(135deg, rgba(59,130,246,0.07) 0%, rgba(186,215,252,0.15) 50%, rgba(59,130,246,0.07) 100%);
}
.header-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: 22px; font-weight: 600; letter-spacing: 4px; text-transform: uppercase; color: #1E3A5F;
}
.ornament { color: #3B82F6; font-size: 11px; opacity: 0.7; }

.close-btn {
  position: absolute; top: 16px; right: 16px;
  width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;
  background: transparent; border: 1px solid rgba(59,130,246,0.25); border-radius: 50%;
  color: #5B82A8; cursor: pointer; transition: all 0.2s ease;
}
.close-btn:hover { background: #3B82F6; color: white; border-color: #3B82F6; transform: rotate(90deg); }

.modal-body { padding: 20px 28px; display: flex; flex-direction: column; gap: 16px; }

.divider-ornament { display: flex; align-items: center; gap: 10px; color: #93C5FD; margin: 0 0 2px; }
.divider-ornament .line { flex: 1; height: 1px; background: linear-gradient(90deg, transparent, #BAD7FC, transparent); }

.info-section {
  background: #F0F7FF; border: 1px solid rgba(59,130,246,0.18); border-radius: 6px; padding: 16px 18px;
}
.section-label {
  display: flex; align-items: center; gap: 7px;
  font-size: 10px; font-weight: 500; letter-spacing: 2.5px; text-transform: uppercase;
  color: #3B82F6; margin: 0 0 12px; padding-bottom: 10px; border-bottom: 1px dashed rgba(59,130,246,0.2);
}
.info-grid { display: flex; flex-direction: column; gap: 7px; }
.info-row { display: flex; align-items: flex-start; gap: 10px; font-size: 14px; color: #1E3A5F; font-weight: 300; line-height: 1.5; }
.info-icon { font-size: 13px; flex-shrink: 0; margin-top: 1px; }

.dish-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 6px; }
.dish-item { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 300; color: #1E3A5F; }
.dish-dot { color: #93C5FD; font-size: 7px; flex-shrink: 0; }
.dish-price { margin-left: auto; font-size: 13px; color: #5B82A8; font-family: 'Cormorant Garamond', serif; }

.total-section {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 20px;
  background: linear-gradient(135deg, #1D4ED8 0%, #3B82F6 55%, #60A5FA 100%);
  border-radius: 6px;
  box-shadow: 0 4px 20px rgba(59,130,246,0.38);
}
.total-label { font-size: 11px; font-weight: 500; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.8); }
.total-amount { font-family: 'Cormorant Garamond', serif; font-size: 26px; font-weight: 600; color: white; text-shadow: 0 1px 4px rgba(0,40,120,0.2); }
.currency { font-size: 16px; margin-left: 2px; }

.qr-section {
  background: #F0F7FF; border: 1px solid rgba(59,130,246,0.18); border-radius: 6px;
  padding: 20px 18px; display: flex; flex-direction: column; align-items: center;
}
.qr-frame { position: relative; padding: 12px; background: white; border: 1px solid rgba(59,130,246,0.15); border-radius: 4px; }
.qr-corner { position: absolute; width: 16px; height: 16px; }
.qr-corner.tl { top:-1px; left:-1px; border-top:2px solid #3B82F6; border-left:2px solid #3B82F6; }
.qr-corner.tr { top:-1px; right:-1px; border-top:2px solid #3B82F6; border-right:2px solid #3B82F6; }
.qr-corner.bl { bottom:-1px; left:-1px; border-bottom:2px solid #3B82F6; border-left:2px solid #3B82F6; }
.qr-corner.br { bottom:-1px; right:-1px; border-bottom:2px solid #3B82F6; border-right:2px solid #3B82F6; }
.qr-image { display: block; width: 200px; height: 200px; object-fit: contain; }
.qr-note { margin: 12px 0 0; font-size: 11px; color: #5B82A8; letter-spacing: 0.5px; text-align: center; }

.modal-footer { padding: 12px 28px 20px; display: flex; justify-content: center; border-top: 1px solid rgba(59,130,246,0.15); }
.btn-close-main {
  padding: 10px 44px;
  border: 1.5px solid #3B82F6; background: transparent; color: #1E3A5F;
  font-family: 'Jost', sans-serif; font-size: 11px; font-weight: 500; letter-spacing: 3px; text-transform: uppercase;
  border-radius: 4px; cursor: pointer; transition: all 0.25s ease; position: relative; overflow: hidden;
}
.btn-close-main::before {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(135deg, #1D4ED8 0%, #60A5FA 100%);
  transform: translateX(-100%); transition: transform 0.3s ease; z-index: 0;
}
.btn-close-main:hover::before { transform: translateX(0); }
.btn-close-main:hover { color: white; }
.btn-close-main span { position: relative; z-index: 2; }

.petal { position: absolute; color: #BAD7FC; opacity: 0.35; pointer-events: none; user-select: none; }
.petal-1 { top:58px; left:12px; transform:rotate(-20deg); font-size:20px; }
.petal-2 { top:58px; right:14px; transform:rotate(20deg); font-size:17px; }
.petal-3 { bottom:68px; left:14px; transform:rotate(15deg); font-size:15px; }
.petal-4 { bottom:68px; right:12px; transform:rotate(-15deg); font-size:18px; }

.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.3s ease; }
.modal-fade-enter-active .modal-container,
.modal-fade-leave-active .modal-container { transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), opacity 0.3s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }
.modal-fade-enter-from .modal-container { transform: translateY(22px) scale(0.97); opacity: 0; }
.modal-fade-leave-to .modal-container { transform: translateY(10px) scale(0.98); opacity: 0; }
</style>