import axios from '../js/composables/axios'

export const getDish = async (search = '') => {
    return axios.get(route('dish.getDish'), {
        params: {search}
    })
}

export const getDishGroup = async () =>{
    return axios.get(route('dish.getAllDishGroup'))
}

export const getDishById = async (id) =>{
    return axios.get(route('dish.getById', id))
}

export const updateDish = async (id, dish) =>{
    return axios.put(route('dish.updateDish', id), dish)
}
