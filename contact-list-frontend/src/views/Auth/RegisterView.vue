<template>
    <div class="register-view">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2>Create Account</h2>
                            <p class="text-muted">Get started with your contact manager</p>
                        </div>

                        <RegisterForm />

                        <div class="text-center mt-4">
                            <p class="mb-0">
                                Already have an account?
                                <router-link to="/login">Sign in</router-link>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import RegisterForm from '../../components/Auth/RegisterForm.vue'
</script>

<style scoped>
.register-view {
    padding-top: 5rem;
    min-height: 100vh;
}

.card {
    border: none;
    border-radius: 0.5rem;
    background-color: #e9edf1;
}

.text-center h2 {
    font-weight: 600;
    color: #343a40;
}

.text-muted {
    color: #6c757d !important;
}

a {
    color: #0d6efd;
    text-decoration: none;
    transition: color 0.2s;
}

a:hover {
    color: #0b5ed7;
}
</style>
