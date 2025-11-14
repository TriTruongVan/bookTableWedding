import axios from '../js/composables/axios'

export const getAllWards = async () => {
  return axios.get(route('customer.getAllWards'))
}