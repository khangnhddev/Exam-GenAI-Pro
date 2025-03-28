import router from '../router'

export const handleApiError = (error) => {
  if (!error.response) {
    // Network error or server is down
    router.push('/500')
    return
  }

  const { status } = error.response

  switch (status) {
    case 400:
      // Bad request - user input error
      router.push('/')
      break

    case 401:
      // Unauthorized
      router.push('/login')
      break

    case 403:
      // Forbidden - redirect home
      router.push('/')
      break

    case 404:
      // Not found - redirect home
      router.push('/')
      break

    case 422:
      // Validation errors - redirect home
      //router.push('/')
      break

    case 500:
      // Server error
      router.push('/500')
      break

    default:
      // Unknown error - redirect home
      router.push('/')
      break
  }
}

// Axios interceptor setup
export const setupAxiosInterceptors = (axios) => {
  axios.interceptors.response.use(
    response => response,
    error => {
      handleApiError(error)
      return Promise.reject(error)
    }
  )
} 