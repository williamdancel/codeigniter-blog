<script setup>
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

async function handleLogout() {
    try { 
        await auth.logout()
    } catch (err) {
        console.error('Logout failed', err)
    } finally {
        router.push({ name: 'home'} )
    }
    
}
</script>

<template>
    <div class="max-w-2xl mx-auto mt-10">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p v-if="auth.user">Welcome, {{ auth.user.username }}!</p>
        <button @click="handleLogout" class="mt-4 bg-red-600 text-white px-4 py-2 rounded">
        Logout
        </button>
    </div>
</template>