<template>
    <div class="card">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Login</h2>
            <form @submit.prevent="handleLogin">
                <div v-if="error" class="alert alert-danger">{{ error }}</div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" v-model="form.email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" v-model="form.password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100" :disabled="isLoading">
                    <span v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></span>
                    Login
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const form = ref({
    email: '',
    password: ''
})
const error = ref(null)
const isLoading = ref(false)

const handleLogin = async () => {
    error.value = null
    isLoading.value = true
    try {
        await authStore.login(form.value)
        router.push('/')
    } catch (err) {
        error.value = 'Invalid email or password'
    } finally {
        isLoading.value = false
    }
}

</script>