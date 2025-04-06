import axios from 'axios'
const API_URL = 'http://localhost:8000/api'

const register = async (userData) => {
  const response = await axios.post(API_URL + '/register', {
    name: userData.name,
    email: userData.email,
    password: userData.password,
    password_confirmation: userData.password_confirmation,
  })
  return response.data
}

const login = async (credentials) => {
  const response = await axios.post(API_URL + '/login', {
    email: credentials.email,
    password: credentials.password,
  })
  if (response.data.token) {
    localStorage.setItem('user', JSON.stringify(response.data))
  }
  return response.data
}

const logout = async () => {
  const token = localStorage.getItem('token')
  const response = await axios.post(
    API_URL + '/logout',
    {},
    {
      headers: {
        Authorization: 'Bearer ' + token,
      },
    },
  )
  return response
}

export default {
  register,
  login,
  logout,
}
