<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <router-link to="/" class="navbar-brand d-flex align-items-center">
                <i class="bi bi-journal-bookmark-fill me-2"></i>
                Contact Manager
            </router-link>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Left Side Links -->
                <ul class="navbar-nav me-auto" v-if="isAuthenticated">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link" active-class="active" exact-active-class="active">
                            <i class="bi bi-house-door me-1"></i>
                            Home
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/contacts" class="nav-link" active-class="active" exact-active-class="active">
                            <i class="bi bi-person-lines-fill me-1"></i>
                            Contacts
                        </router-link>
                    </li>
                </ul>

                <!-- Right Side Links -->
                <ul class="navbar-nav ms-auto">
                    <template v-if="isAuthenticated">
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar-sm me-2">
                                    <div
                                        class="avatar-initials bg-light text-dark rounded-circle d-flex align-items-center justify-content-center">
                                        {{ userInitials }}
                                    </div>
                                </div>
                                {{ currentUser.name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <button @click="logout" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Logout
                                    </button>
                                </li>
                            </ul>
                        </li>
                    </template>

                    <template v-else>
                        <li class="nav-item">
                            <router-link to="/login" class="nav-link" active-class="active" exact-active-class="active">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Login
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/register" class="nav-link" active-class="active"
                                exact-active-class="active">
                                <i class="bi bi-person-plus me-1"></i>
                                Register
                            </router-link>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

const isAuthenticated = computed(() => authStore.isAuthenticated)
const currentUser = computed(() => authStore.user)
const unreadCount = computed(() => 0) // Replace with actual notification count

const userInitials = computed(() => {
    if (!currentUser.value?.name) return ''
    const names = currentUser.value.name.split(' ')
    return names.map(name => name[0]).join('').toUpperCase()
})

const logout = async () => {
    await authStore.logout()
    router.push('/login')
}
</script>

<style scoped>
.navbar {
    padding: 0.75rem 1rem;
}

.navbar-brand {
    font-weight: 600;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-initials {
    width: 100%;
    height: 100%;
    font-weight: 600;
    font-size: 0.875rem;
}

.dropdown-menu {
    margin-top: 0.5rem;
}

.badge {
    font-size: 0.6rem;
    padding: 0.25em 0.4em;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
}

.nav-link:hover {
    opacity: 0.9;
}

.nav-link i {
    font-size: 1.1rem;
}

.active {
    font-weight: 500;
    position: relative;
}

.active:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 1rem;
    right: 1rem;
    height: 2px;
    background-color: white;
}
</style>