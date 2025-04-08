<template>
  <Navbar />
  <main class="container mb-4">
    <router-view />
  </main>
  <!-- Footer -->
  <footer class="bg-primary py-4 ">
    <div class="container text-center text-muted">
      <small>&copy; {{ new Date().getFullYear() }} Contact Manager. All rights reserved.</small>
    </div>
  </footer>
</template>

<script setup>
import Navbar from '@/components/Layouts/Navbar.vue';
</script>

<style>
main {
  min-height: calc(100vh - 120px);
}
</style>