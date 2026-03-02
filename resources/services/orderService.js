import axios from '../js/composables/axios'

export const createOrder = async (payload) => {
    return axios.post(route('order.create'), payload)
}

export const getOrders = async (
  page = 1,
  size = 20,
  search = '',
  staff_id = null
) => {
  return axios.get(route('order.getOrder'), {
    params: {
      page,
      size,
      search,
      ...(staff_id ? { staff_id } : {})
    }
  })
}

export const getOrderById = async (id) => {
    return axios.get(route('order.getById', id))
}

export const updateOrder = async (id, payload) =>{
    return axios.put(route('order.update', id), payload)
}