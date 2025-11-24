import axios from '../js/composables/axios'

export const getAllWards = async () => {
  return axios.get(route('customer.getAllWards'))
}

export const getUser = async () => {
  return axios.get(route('user.getUser'))
}

export const updateUser = async (id, user) =>{
  return axios.put(route('user.update.info', id), user)
}

export const updatePass = async (password) => {
  return axios.put(route('user.update.pass'), password)
}