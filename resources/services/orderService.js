import axios from '../js/composables/axios'

export const createOrder = async (payload) => {
    return axios.post(route('order.create'), payload)
}