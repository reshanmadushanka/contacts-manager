<template>
    <form @submit.prevent="handleSubmit" class="auth-form">
        <div v-if="error" class="alert alert-danger">{{ error }}</div>

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" v-model="form.name" required autofocus>
            <div v-if="errors.name" class="invalid-feedback d-block">
                {{ errors.name[0] }}
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" v-model="form.email" required>
            <div v-if="errors.email" class="invalid-feedback d-block">
                {{ errors.email[0] }}
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" v-model="form.password" required minlength="8">
            <div class="form-text">Minimum 8 characters</div>
            <div v-if="errors.password" class="invalid-feedback d-block">
                {{ errors.password[0] }}
            </div>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" v-model="form.password_confirmation"
                required>
            <div v-if="errors.password_confirmation" class="invalid-feedback d-block">
                {{ errors.password_confirmation[0] }}
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 mb-3" :disabled="isLoading">
            <span v-if="isLoading" class="spinner-border spinner-border-sm me-2" role="status"></span>
            {{ isLoading ? 'Creating Account...' : 'Create Account' }}
        </button>
    </form>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
})

const errors = ref({})
const error = ref(null)
const isLoading = ref(false)

const handleSubmit = async () => {
    errors.value = {}
    error.value = null
    isLoading.value = true

    if (form.value.password !== form.value.password_confirmation) {
        errors.value.password_confirmation = 'Passwords do not match'
        isLoading.value = false
        return
    }

    try {
        await authStore.register(form.value)
        router.push('/')
    } catch (err) {
        if (err.response?.data?.errors) {
            errors.value = err.response.data.errors
        } else {
            error.value = err.response?.data?.message || err.message || 'Registration failed'
        }
    } finally {
        isLoading.value = false
    }
}
</script>

<style scoped>
.auth-form {
    max-width: 400px;
    margin: 0 auto;
}

.form-control {
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
}

.btn-primary {
    border-radius: 0.5rem;
    font-weight: 500;
    padding: 0.75rem;
}
</style>