<template>
    <div class="login-view">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2>Welcome Back</h2>
                            <p class="text-muted">Sign in to manage your contacts</p>
                        </div>

                        <LoginForm @login-success="handleLoginSuccess" />

                        <div class="text-center mt-4">
                            <p class="mb-0">
                                Don't have an account?
                                <router-link to="/register">Sign up</router-link>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import LoginForm from '@/components/Auth/LoginForm.vue'
</script>

<style scoped>
.login-view {
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