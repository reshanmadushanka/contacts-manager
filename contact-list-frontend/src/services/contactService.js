import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
})

// Attach token automatically
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

const getContacts = async (page = 1, perPage = 5, search = '') => {
  const response = await api.get('/contacts', {
    params: { page, per_page: perPage, search },
  })
  return response.data
}

const getContact = async (id) => {
  const response = await api.get(`/contacts/${id}`)
  return response.data
}

const createContact = async (contactData) => {
  const response = await api.post('/contacts', contactData)
  return response.data
}

const updateContact = async (id, contactData) => {
  const response = await api.put(`/contacts/${id}`, contactData)
  return response.data
}

const deleteContact = async (id) => {
  const response = await api.delete(`/contacts/${id}`)
  return response.data
}

export default {
  getContacts,
  getContact,
  createContact,
  updateContact,
  deleteContact,
}
