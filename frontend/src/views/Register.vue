<script setup>
import { ref, computed } from 'vue'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()

const form = ref({ username: '', email: '', password: ''})
const errors = ref({})
const successMessage = ref('')

const passwordsMatch = computed(() => {
    if(!form.value.password_confirm) return true
    return form.value.password === form.value.password_confirm
})

async function handleSubmit() 
{
    errors.value = {}
    successMessage.value = ''

    if(form.value.password != form.value.password_confirm) {
        errors.value.password_confirm = 'Password do not match.'
        return
    }

    try {
        const { username, email, password} = form.value
        const res = await auth.register({ username, email, password})
        successMessage.value = res.successMessage
        form.value = { username: '', email: '', password: '', password_confirm: ''}
    } catch(err) {
        errors.value = { ...errors.value, ...err.errors }
    }
}

</script>

<template>
    <form @submit.prevent="handleSubmit" class="max-w-sm mx-auto mt-10 space-y-4">
        <h2 class="text-2xl font-bold">Register</h2>

        <div>
            <input v-model="form.username" type="text" placeholder="Username" class="w-full border rounded p-2" />
            <p v-if="errors.username" class="text-red-500 text-sm">{{errors.username}}</p>
        </div>

        <div>
            <input v-model="form.email" type="email" placeholder="Email" class="w-full border rounded p-2" />
            <p v-if="errors.email" class="text-red-500 text-sm">{{errors.email}}</p>
        </div>

        <div>
            <input v-model="form.password" type="password" placeholder="Password" class="w-full border rounded p-2"/>
            <p v-if="errors.password" class="text-red-500 text-sm">{{ errors.password }}</p>
        </div>
        <div>
            <input v-model="form.password_confirm" type="password" placeholder="Confirm Password" 
            class="w-full border rounded p-2" :class="{ 'border-red-500': !passwordsMatch }">
            <p v-if="!passwordsMatch" class="text-red-500" text-sm>{{ errors.password_confirm }}</p>
        </div>

        <button type="submit" :disabled="auth.loading || !passwordsMatch"
        class="w-full bg-blue-600 text-white rounded p-2 font-bold">
        {{ auth.loading ? 'Registering...' : 'Register' }}
        </button>

        <p v-if="successMessage" class="text-green-600"> {{ successMessage }}</p>
    </form>
</template>