import axios from '../js/composables/axios'

export const getAllCustomers = async (page = 1, size = 20, sortBy ='created_at', sortDir = 'desc') => {
    return axios.get(route('customer.getAll', {page, size, sortBy, sortDir}))
}

export const getCustomersByTelOrName = async (search = '') => {
  return axios.get(route('customer.search', { search }))
}

export const create = async (data) => {
  return axios.post(route('customer.create'), data)
}

export const getCustomerById = async(id) => {
  return axios.get(route('customer.getById', id))
}

export const updateCustomer = async (id, customer) => {
  return axios.put(route('customer.update', id), customer)
}

export const getCustomersByTel = async (search = '') => {
  return axios.get(route('customer.getByTel', {search}))
}
