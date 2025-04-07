import { defineStore } from 'pinia'
import contactService from '@/services/contactService'
import Swal from 'sweetalert2'

export const useContactStore = defineStore('contacts', {
  state: () => ({
    contacts: [],
    isLoading: false,
    error: [],
  }),
  actions: {
    async fetchContacts(page = 1, perPage = 5, search = '') {
      this.isLoading = true
      this.error = null
      try {
        const response = await contactService.getContacts(page, perPage, search)
        this.contacts = response
      } catch (error) {
        this.error = error.response?.data?.errors || error.message
        throw error
      } finally {
        this.isLoading = false
      }
    },
    async fetchContact(id) {
      this.isLoading = true
      this.error = null
      try {
        const response = await contactService.getContact(id)
        return response
      } catch (error) {
        this.error = error.response?.data?.errors || error.message
        throw error
      } finally {
        this.isLoading = false
      }
    },
    async createContact(contactData) {
      this.error = null
      try {
        const response = await contactService.createContact(contactData)
        Swal.fire({
          position: 'top-end',
          title: 'Added!',
          text: response.message,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500,
        })
        return response
      } catch (error) {
        this.error = error.response?.data?.errors || error.message
        throw error
      }
    },
    async updateContact(id, contactData) {
      this.error = null
      try {
        const response = await contactService.updateContact(id, contactData)
        const index = this.contacts.data.data.findIndex((contact) => contact.id === id)
        if (index !== -1) {
          this.contacts.data.data[index] = response.data
        }
        Swal.fire({
          position: 'top-end',
          title: 'Updated!',
          text: response.message,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500,
        })
        return response
      } catch (error) {
        this.error = error.response?.data?.errors || error.message
        throw error
      }
    },
    async deleteContact(id) {
      this.error = null
      try {
        await contactService.deleteContact(id)
        const index = this.contacts.data.data.findIndex((contact) => contact.id === id)
        if (index !== -1) {
          this.contacts.data.data.splice(index, 1) // Remove the deleted contact from the array
        }
        Swal.fire({
          position: 'top-end',
          title: 'Deleted!',
          text: 'Your contact has been deleted.',
          icon: 'success',
          showConfirmButton: false,
          timer: 1500,
        })
      } catch (error) {
        this.error = error.response?.data?.message || error.message
        throw error
      }
    },
    resetError() {
      this.error = null
    },
  },
})
