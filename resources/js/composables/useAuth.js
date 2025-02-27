import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const user = ref(null)

export function useAuth() {
  const router = useRouter()

  async function logout() {
    try {
      await axios.post('/logout')
      user.value = null
      router.push({ name: 'login' })
    } catch (error) {
      console.error('Error during logout:', error)
    }
  }

  function updateUser(userData) {
    user.value = userData
  }

  return {
    user,
    logout,
    updateUser
  }
}