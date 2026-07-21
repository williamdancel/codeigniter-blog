<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const form = ref({ email:'', password: ''})
const errorMessage = ref('')

async function handleSubmit() {
    errorMessage.value = ''
    try {
        await auth.login(form.value)
    } catch(err) {
        errorMessage.value = err.message ?? 'Login failed.'
    }
}
</script>

<template>
    <form @submit.prevent="handleSubmit" class="max-w-sm mx-auto mt-10 space-y-4">
        <h2 class="text-2xl font-bold">Login</h2>
    
        <input v-model="form.email" type="email" placeholder="Email" />
        <input v-model="form.password" type="password" placeholder="Password" />

        <button type="submit" :disabled="auth.loading" class="w-full bg-blue-600 text-white rounded p-2 font-bold">
            {{ auth.loading ? 'Logging in...' : 'Login' }}
        </button>

        <p v-if="errorMessage" class="text-red-500">{{ errorMessage }}</p>

        <div v-if="auth.isLoggedIn" class="text-green-600">
            Logged in as {{ auth.user.username }}
        </div>
    </form>
</template>