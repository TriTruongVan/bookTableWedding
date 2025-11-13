import axios from '../js/composables/axios'

export const getAllCustomers = async (page = 1, size = 20, sortBy ='created_at', sortDir = 'desc') => {
    return axios.get(route('customer.getAll', {page, size, sortBy, sortDir}))
}