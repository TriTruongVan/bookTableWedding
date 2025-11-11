import axios from '../js/composables/axios'

let currentUser = null

export const login = async (tel, password) => {
  const resp = await axios.post(route('login.admin'), { tel, password })

  const token = resp?.data?.data?.token
  if (token) {
    localStorage.setItem('token', token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  }

  return resp
}

export const logout = () => {
  return new Promise((resolve) => {
    setTimeout(() => {
      localStorage.removeItem('token')
      delete axios.defaults.headers.common['Authorization']
      resolve()
    }, 300)
  })
}

export const getCurrentUser = () => {
  const token = localStorage.getItem('token')
  if (!token) return null

  try {
    const payload = token.split('.')[1]
    const decodedPayload = JSON.parse(atob(payload))
    currentUser = decodedPayload
    return currentUser
  } catch (error) {
    return null
  }
}

export const isAuthenticated = () => {
  const token = localStorage.getItem('token')
  if (!token) return false

  try {
    const payload = JSON.parse(atob(token.split('.')[1]))
    const currentTime = Math.floor(Date.now() / 1000)
    return payload.exp && payload.exp > currentTime
  } catch (error) {
    return false
  }
}