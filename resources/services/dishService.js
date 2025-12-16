import axios from '../js/composables/axios'

export const getDish = async () => {
    return axios.get(route('service.getService'))
}

export const getDishGroup = () =>{
    return axios.get(route('serviceGroup.getService'))
}
