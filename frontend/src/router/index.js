import { createRouter, createWebHashHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Dashboard from '../views/Dashboard.vue'

const routes = [
    { path: '/', name: 'home', component: Home},
    { path: '/login', name: 'login', component: Login},
    { path: '/register', name: 'register', component: Register},
    { path: '/dashboard',
        name: 'dashboard',
        component: Dashboard, 
        meta: { requiresAuth:true },
    }
]

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

router.beforeEach(async (to) =>{
  const auth = useAuthStore()
  
  if(auth.user === null){
    await auth.fetchUser()
  }

  if(to.meta.requiresAuth && !auth.isLoggedIn){
    return {name: 'login'}
  }
})

export default router