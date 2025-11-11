import { ORDER_CONFIRM_STATUS, ORDER_STATUS } from "@/utils/constants"

export const OTHER_SERVICE_ID = 0
export interface ServiceItemInterface {
  id: number | string
  name: string
  description?: string
  laborCost: number
  materialCost: number
  travelCost: number
  platformFee: number
  baseCost?: number
  serviceGroupName: string
  createdAt?: string
  updatedAt?: string
}
export interface ServiceInterface extends ServiceItemInterface {
  serviceId: number | string
  note?: string
  renderId?: string | number
  serviceName: string
}
export interface NewServiceInterface extends ServiceInterface {
  note?: string
}
export interface VoucherInterface {
  id: string
  code: string
  type: 'percentage' | 'fixed'
  discountType: 0 | 1
  discountValue: number
  value: number
  maxDiscount?: number
  minOrderValue?: number
  usageLimit?: number
  usedCount?: number
  validFrom?: string
  validUntil?: string
  createdAt?: string
  updatedAt?: string
  targetFeeType: 0 | 1
}

export type OrderStatus = typeof ORDER_STATUS[keyof typeof ORDER_STATUS];
export type OrderConfirmStatus = typeof ORDER_CONFIRM_STATUS[keyof typeof ORDER_CONFIRM_STATUS];

export interface OrderInterface {
  id?: string
  orderItems: ServiceInterface[]
  scheduledAt?: string
  status: OrderStatus
  vouchers: VoucherInterface[]
  services: ServiceItemInterface[]
  canAccess?: boolean
  hasPause: boolean
  hasResume: boolean,
  canMarkDone: boolean,
  encodedId: string,
  hasPausedHistory: boolean
}

export interface OrderFormInterface {
  customer: CustomerFormInterface
  services: ServiceInterface[]
  voucherIds: string[]
  staffId?: string
  scheduledAt?: Date
  note: string
}

export interface CustomerFormInterface {
  name: string
  tel: string
  street: string
  ward?: number
}

export interface WardInterface {
  id: number
  label: string
}

export interface StaffInterface {
  id: string
  tel: string
  name: string
}

export interface ServiceProgressInterface {
  id: string
  staffName: string
  staffId: string
  status: OrderStatus
  note: string
  hasAdditionalService: number
  imgPath: string
  avtPath: string
  location: string
  createdDate: string
  read: boolean
}


export interface FeedbackInterface {
  id: number
  star: number
  note: string
}
