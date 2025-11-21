import axios from '../js/composables/axios'

export const getAllStaff = async (page = 1, size = 20, sortBy ='created_at', sortDir = 'desc') => {
    return axios.get(route('staff.get', {page, size, sortBy, sortDir}))
}

export const getStaffByTelOrName = async (search = '') => {
    return axios.get(route('staff.search', {search}))
}

export const create = async (data) => {
    return axios.post(route('staff.create'), data)
}

export const getStaffById = async(id) => {
    return axios.get(route('staff.getById', id))
}

export const updateStaff = async (id, staff) => {
    return axios.put(route('staff.update', id), staff)
}
