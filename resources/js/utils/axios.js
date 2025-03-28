import axios from 'axios'
import { getErrorType, getErrorMessage } from './errorHandler'
import router from '@/router'

const instance = axios.create({
  baseURL: '/api',
  headers: {
    'X-Requested-With': 'XMLHttpRequest'
  }
})

instance.interceptors.response.use(
  response => response,
  error => {
    const errorType = getErrorType(error)
    const errorMessage = getErrorMessage(error)
    const errorCode = error.response?.status

    // Don't redirect for validation errors
    if (errorType !== 'VALIDATION') {
      router.push({
        name: 'error',
        params: {
          type: errorType,
          message: errorMessage,
          errorCode
        }
      })
    }

    return Promise.reject(error)
  }
)

export default instance