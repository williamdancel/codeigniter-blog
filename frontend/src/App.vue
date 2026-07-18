<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const message = ref('Loading...');

onMounted(async () =>{
  try{
    const res = await axios.get('http://localhost:8080/api/test')
    message.value = res.data.message
  } catch (err){
    message.value = 'API connection failed:' + err.message
  }
})
</script>

<template>
  <div class="p-8 text-3xl font-bold text-blue-600">
    {{ message }}
  </div>
</template>
