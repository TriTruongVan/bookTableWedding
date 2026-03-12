<script setup>
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { onMounted, ref } from 'vue'
import { useToast } from 'primevue/usetoast'
import { getOrders } from '../../../services/orderService'
import { useRouter } from 'vue-router'

const toast = useToast()
const loading = ref(false)
const listOrder = ref([])
const router = useRouter()

const handleEventClick = (info) => {
    const orderID = info.event.id
    router.push(`/orders/${orderID}`)
}

const calendarOptions = ref({
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    displayEventTime: false,
    eventDisplay: 'block',
    eventOrder: 'sessionOrder',
    events: [],
    eventClick: handleEventClick,
    height: 'auto',
    buttonText: {
        today: 'Hôm nay',
        month: 'Tháng',
    },
    titleFormat: { year: 'numeric', month: 'long' }
})

const getSessionName = (session) => {
    if (session === 'trua') return 'Trưa'
    if (session === 'chieu') return 'Chiều'
    return session
}
const getSessionOrder = (session) => {
    if (session === 'trua') return 1
    if (session === 'chieu') return 2
    return 99
}
const getSessionColor = (session) => {
    if (session === 'trua') return '#e8a045'
    if (session === 'chieu') return '#7b6ef6'
    return '#94a3b8'
}
const getStatusIcon = (status) => {
    if (status === 'ket_thuc') return '✓ '
    return ''
}

onMounted(async () => {
    await loadOrder()
})

const loadOrder = async () => {
    loading.value = true
    try {
        const resp = await getOrders()
        listOrder.value = resp.data.data.data

        calendarOptions.value.events = listOrder.value.map(order => ({
            id: order.id,
            title: `${getStatusIcon(order.status)}${getSessionName(order.session)} · ${order.customer.name} · ${order.table_count} bàn`,
            date: order.event_date,
            sessionOrder: getSessionOrder(order.session),
            backgroundColor: getSessionColor(order.session),
            borderColor: 'transparent',
            textColor: '#ffffff'
        }))
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Lỗi',
            detail: 'Không tải được lịch tiệc. Vui lòng thử lại.',
            life: 4000
        })
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="page">
        <!-- Background texture -->
        <div class="bg-texture"></div>

        <div class="page-header">
            <div class="page-title-group">
                <div class="page-icon-wrap">
                    <span class="page-icon">🗓</span>
                </div>
                <div>
                    <h1 class="page-title">Lịch Tiệc</h1>
                    <p class="page-desc">Quản lý đặt tiệc theo ngày</p>
                </div>
            </div>
            <div class="legend">
                <span class="badge badge-trua">
                    <span class="dot dot-trua"></span>
                    Buổi Trưa
                </span>
                <span class="badge badge-chieu">
                    <span class="dot dot-chieu"></span>
                    Buổi Chiều
                </span>
                <span class="badge badge-done">
                    <svg class="check-icon" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="6" cy="6" r="6" fill="#3dba7e" fill-opacity="0.18"/>
                        <path d="M3.5 6.2L5.2 7.9L8.5 4.5" stroke="#3dba7e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Đã kết thúc
                </span>
            </div>
        </div>

        <div v-if="loading" class="loading">
            <div class="loader-ring">
                <div></div><div></div><div></div><div></div>
            </div>
            <span>Đang tải lịch tiệc…</span>
        </div>

        <div v-else class="cal-card">
            <FullCalendar :options="calendarOptions" />
        </div>
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap');

/* ─── Variables ───────────────────────────────────── */
.page {
    --gold: #e8a045;
    --gold-light: #f5c87a;
    --violet: #7b6ef6;
    --violet-light: #a89cf8;
    --surface: #ffffff;
    --surface-2: #fafafa;
    --bg: #f4f5f7;
    --border: #e8eaed;
    --border-soft: #f0f1f3;
    --text: #111317;
    --text-2: #3d4148;
    --muted: #8b909a;
    --green: #3dba7e;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.07), 0 2px 6px rgba(0,0,0,0.04);
    --shadow-lg: 0 12px 40px rgba(0,0,0,0.10), 0 4px 12px rgba(0,0,0,0.05);
    --radius: 16px;
    --radius-sm: 10px;

    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    min-height: 100vh;
    padding: 32px 36px;
    position: relative;
    overflow-x: hidden;
}

/* ─── Background texture ──────────────────────────── */
.bg-texture {
    position: fixed;
    inset: 0;
    background-image:
        radial-gradient(circle at 15% 15%, rgba(123,110,246,0.06) 0%, transparent 50%),
        radial-gradient(circle at 85% 80%, rgba(232,160,69,0.06) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
}

.page > * { position: relative; z-index: 1; }

/* ─── Header ──────────────────────────────────────── */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 28px;
}

.page-title-group {
    display: flex;
    align-items: center;
    gap: 16px;
}

.page-icon-wrap {
    width: 52px;
    height: 52px;
    background: var(--surface);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    font-size: 24px;
    flex-shrink: 0;
}

.page-title {
    font-family: 'Instrument Serif', serif;
    font-size: 26px;
    font-weight: 400;
    color: var(--text);
    margin: 0 0 2px;
    letter-spacing: -0.3px;
    line-height: 1.2;
}

.page-desc {
    font-size: 13px;
    color: var(--muted);
    margin: 0;
    font-weight: 400;
    letter-spacing: 0.1px;
}

/* ─── Legend ──────────────────────────────────────── */
.legend {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    background: var(--surface);
    padding: 6px 10px;
    border-radius: 12px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
}

.badge {
    font-size: 12.5px;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 7px;
    color: var(--text-2);
    transition: background 0.15s;
    cursor: default;
    letter-spacing: 0.1px;
}

.badge:hover { background: var(--bg); }

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

.dot-trua  { background: var(--gold); box-shadow: 0 0 0 2px rgba(232,160,69,0.2); }
.dot-chieu { background: var(--violet); box-shadow: 0 0 0 2px rgba(123,110,246,0.2); }
.check-icon { width: 14px; height: 14px; flex-shrink: 0; }

/* ─── Loading ─────────────────────────────────────── */
.loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    justify-content: center;
    padding: 80px;
    background: var(--surface);
    border-radius: var(--radius);
    font-size: 13px;
    color: var(--muted);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-md);
    font-weight: 500;
}

/* Elegant ring loader */
.loader-ring {
    display: inline-block;
    position: relative;
    width: 36px;
    height: 36px;
}
.loader-ring div {
    box-sizing: border-box;
    display: block;
    position: absolute;
    width: 30px; height: 30px;
    margin: 3px;
    border: 2.5px solid transparent;
    border-top-color: var(--violet);
    border-radius: 50%;
    animation: ring-spin 1s cubic-bezier(0.5, 0, 0.5, 1) infinite;
}
.loader-ring div:nth-child(1) { animation-delay: -0.3s; border-top-color: var(--violet); }
.loader-ring div:nth-child(2) { animation-delay: -0.2s; border-top-color: var(--violet-light); }
.loader-ring div:nth-child(3) { animation-delay: -0.1s; border-top-color: rgba(123,110,246,0.3); }
.loader-ring div:nth-child(4) { border-top-color: transparent; }

@keyframes ring-spin {
    0%   { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* ─── Calendar card ───────────────────────────────── */
.cal-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    padding: 28px 28px 24px;
    box-shadow: var(--shadow-lg);
    animation: fadeUp 0.4s ease both;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ─── FullCalendar overrides ──────────────────────── */

/* Toolbar */
.fc .fc-toolbar.fc-header-toolbar {
    margin-bottom: 24px !important;
    padding-bottom: 20px !important;
    border-bottom: 1px solid var(--border-soft) !important;
}

.fc .fc-toolbar-title {
    font-family: 'Instrument Serif', serif !important;
    font-size: 20px !important;
    font-weight: 400 !important;
    color: var(--text) !important;
    letter-spacing: -0.2px !important;
}

/* Buttons */
.fc .fc-button {
    background: var(--surface-2) !important;
    color: var(--text-2) !important;
    border: 1px solid var(--border) !important;
    border-radius: var(--radius-sm) !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    font-family: 'DM Sans', sans-serif !important;
    padding: 7px 16px !important;
    box-shadow: var(--shadow-sm) !important;
    transition: all 0.18s ease !important;
    text-transform: none !important;
    letter-spacing: 0.1px !important;
}

.fc .fc-button:hover {
    background: var(--bg) !important;
    border-color: #d4d6db !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 3px 8px rgba(0,0,0,0.09) !important;
}

.fc .fc-button:active {
    transform: translateY(0) !important;
}

/* Prev/Next icon buttons */
.fc .fc-prev-button,
.fc .fc-next-button {
    padding: 7px 11px !important;
}

.fc .fc-button-primary:not(:disabled).fc-button-active {
    background: var(--violet) !important;
    color: #fff !important;
    border-color: var(--violet) !important;
    box-shadow: 0 4px 12px rgba(123,110,246,0.3) !important;
}

.fc .fc-today-button:not(:disabled) {
    background: linear-gradient(135deg, var(--violet), var(--violet-light)) !important;
    color: #fff !important;
    border-color: transparent !important;
    box-shadow: 0 4px 14px rgba(123,110,246,0.35) !important;
    font-weight: 600 !important;
}

.fc .fc-today-button:not(:disabled):hover {
    box-shadow: 0 6px 18px rgba(123,110,246,0.45) !important;
    transform: translateY(-1px) !important;
}

/* Day headers */
.fc .fc-col-header-cell {
    background: transparent !important;
    border-bottom: 1px solid var(--border) !important;
}

.fc .fc-col-header-cell-cushion {
    font-size: 11.5px !important;
    font-weight: 600 !important;
    color: var(--muted) !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    padding: 12px 4px !important;
    text-decoration: none !important;
}

/* Day numbers */
.fc .fc-daygrid-day-number {
    font-size: 13px !important;
    font-weight: 500 !important;
    color: var(--text-2) !important;
    padding: 8px 10px !important;
    text-decoration: none !important;
    border-radius: 8px;
    transition: background 0.12s, color 0.12s;
    display: inline-block !important;
    min-width: 28px;
    text-align: center;
    line-height: 1;
}

.fc .fc-daygrid-day-number:hover {
    background: var(--bg) !important;
    color: var(--text) !important;
}

/* Today */
.fc .fc-day-today {
    background: rgba(123,110,246,0.04) !important;
}

.fc .fc-day-today .fc-daygrid-day-number {
    background: var(--violet) !important;
    color: #fff !important;
    font-weight: 700 !important;
    box-shadow: 0 3px 10px rgba(123,110,246,0.35) !important;
}

/* Other month days */
.fc .fc-day-other {
    background: rgba(0,0,0,0.01) !important;
}
.fc .fc-day-other .fc-daygrid-day-number {
    color: #c8ccd4 !important;
    font-weight: 400 !important;
}

/* Events */
.fc-event {
    cursor: pointer !important;
    border-radius: 7px !important;
    padding: 3px 9px !important;
    font-size: 11.5px !important;
    font-weight: 600 !important;
    font-family: 'DM Sans', sans-serif !important;
    margin-bottom: 3px !important;
    line-height: 1.65 !important;
    border: none !important;
    transition: opacity 0.15s, transform 0.15s, box-shadow 0.15s !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.14) !important;
    letter-spacing: 0.1px !important;
}

.fc-event:hover {
    opacity: 0.92 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 14px rgba(0,0,0,0.18) !important;
}

/* Grid lines */
.fc .fc-scrollgrid {
    border-color: var(--border) !important;
    border-radius: 12px !important;
    overflow: hidden !important;
}

.fc td, .fc th {
    border-color: var(--border-soft) !important;
}

.fc .fc-daygrid-day {
    min-height: 96px !important;
    transition: background 0.15s !important;
}

.fc .fc-daygrid-day:hover {
    background: rgba(0,0,0,0.012) !important;
}

/* More link */
.fc .fc-daygrid-more-link {
    font-size: 11px !important;
    font-weight: 700 !important;
    color: var(--violet) !important;
    padding: 1px 6px !important;
    border-radius: 5px !important;
    background: rgba(123,110,246,0.08) !important;
    transition: background 0.15s !important;
    text-decoration: none !important;
}

.fc .fc-daygrid-more-link:hover {
    background: rgba(123,110,246,0.16) !important;
}

/* Day top area */
.fc .fc-daygrid-day-top {
    padding: 6px 6px 2px !important;
}

/* Remove default highlight on today header */
.fc .fc-col-header-cell.fc-day-today {
    background: transparent !important;
}
</style>