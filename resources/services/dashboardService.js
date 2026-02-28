import axios from '../js/composables/axios'

export const getDashBoardSummary = async () =>{
    return axios.get(route('dashboard.summary'))
}