import { defineStore } from 'pinia'
import { ref, computed, onErrorCaptured } from 'vue'
import api from '../lib/api'


export const useAuthStore = defineStore('auth', () => {
    // state
    const user = ref(null)
    const loading = ref(false)
    const error = ref(null)

    // getters
    const isLoggedIn = computed(() => !!user.value)

    //actions
    async function register(payload) 
    {
        loading.value = true
        error.value = null
        try {
            const res = await api.post('register', payload)
            return res.data
        } catch (err) {
            error.value = err.response?.data ?? {message: 'Registration failed.'}
            throw error.value
        } finally {
            loading.value = false
        }
    }

    async function login(payload)
    {
        loading.value = true
        error.value = null
        try {
            const res = await api.post('/login', payload)
            user.value = res.data.user
            return res.data
        } catch (err) {
            error.value = err.response?.data ?? { message: 'Login failed' }
            throw error.value
        } finally {
            loading.value = false
        }
    }

    async function logout()
    {
        await api.post('/logout')
        user.value = null
    }

    async function logout()
    {
        await api.post('logout')
        user.value = null
    }

    async function fetchUser()
    {
        try {
            const res = await api.get('/user')
            user.value = res.data.user
        } catch {
            user.value =null
        }
    }

    return {user, loading, error, isLoggedIn, register, login, logout, fetchUser }
})
