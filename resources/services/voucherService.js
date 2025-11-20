import axios from '../js/composables/axios'

export const getAllVouchers = async () => {
    return axios.get(route('voucher.getAll'))
}

export const getVouchers = async (page = 1, size = 20, search = '', is_active = '') => {
  return axios.get(route('voucher.get', { page, size, search, is_active }))
}

export const createVoucher = async (data) =>{
  return axios.post(route('voucher.create',data))
}

export const getVoucherById = async(id) => {
  return axios.get(route('voucher.getById', id))
}

export const updateVoucher = async(id, voucher) =>{
  return axios.put(route('voucher.update', id), voucher)
}