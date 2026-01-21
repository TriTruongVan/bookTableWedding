import axios from '../js/composables/axios'

export const createOrder = async (payload) => {
    return axios.post(route('order.create'), payload)
}

export const getOrders = async (page = 1, size = 20, search = '') => {
    return axios.get(route('order.getOrder',{page, size, search}))
}

export const getOrderById = async (id) => {
    return axios.get(route('order.getById', id))
}

export const updateOrder = async (id, payload) =>{
    return axios.put(route('order.update', id), payload)
}