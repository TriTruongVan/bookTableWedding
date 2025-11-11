import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from '../router';
import '../css/app.scss';
import '../css/style.scss'
import { i18n } from './i18n';
import PrimeVue from 'primevue/config'
import 'primeicons/primeicons.css'
import Lara from '@primevue/themes/lara'
import Toast from 'primevue/toast'
import ToastService from 'primevue/toastservice'
import {ZiggyVue} from '../../vendor/tightenco/ziggy'
const app = createApp(App);
app.use(PrimeVue, {
  theme: {
    preset: Lara,
    options: {
      prefix: 'p',
      darkModeSelector: '.dark',
      cssLayer: false
    }
  },
  locale: {
    firstDayOfWeek: 1,
    dayNames: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'],
    dayNamesShort: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
    dayNamesMin: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
    monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
    monthNamesShort: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
    today: 'Hôm nay',
    clear: 'Xóa',
    dateFormat: 'dd/mm/yy',
    weekHeader: 'Tuần'
  }
});

app.use(router);
app.use(i18n);
app.use(ToastService)
app.use(ZiggyVue)
app.component('Toast', Toast)
app.mount('#app');
