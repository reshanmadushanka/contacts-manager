<template>
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">{{ isEdit ? 'Edit Contact' : 'Add New Contact' }}</h2>
            <form @submit.prevent="handleSubmit">
                <div v-if="error" class="alert alert-danger">{{ error }}</div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" v-model="form.name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" v-model="form.email" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" v-model="form.phone_number" required>
                </div>

                <button type="submit" class="btn btn-primary" :disabled="isLoading">
                    <span v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></span>
                    {{ isEdit ? 'Update' : 'Save' }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useContactStore } from '@/stores/contactStore'

const route = useRoute()
const router = useRouter()
const contactStore = useContactStore()

const isEdit = computed(() => route.name === 'contact-edit')

const form = ref({
    name: '',
    email: '',
    phone_number: ''
})

const isLoading = ref(false)
const error = ref(null)

onMounted(async () => {
    if (isEdit.value) {
        isLoading.value = true
        try {
            await contactStore.fetchContact(route.params.id)
            form.value = { ...contactStore.currentContact }
        } catch (err) {
            error.value = err.message
        } finally {
            isLoading.value = false
        }
    }
})

const handleSubmit = async () => {
    isLoading.value = true
    error.value = null

    try {
        if (isEdit.value) {
            await contactStore.updateContact(route.params.id, form.value)
        } else {
            await contactStore.addContact(form.value)
        }
        router.push({ name: 'contacts' })
    } catch (err) {
        error.value = err.message
    } finally {
        isLoading.value = false
    }
}
</script>