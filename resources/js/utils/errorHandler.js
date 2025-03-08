import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'

export const ERROR_TYPES = {
  VALIDATION: 'VALIDATION',
  AUTH: 'AUTH',
  NOT_FOUND: 'NOT_FOUND',
  SERVER: 'SERVER',
  NETWORK: 'NETWORK',
  UNKNOWN: 'UNKNOWN'
}

export const getErrorType = (error) => {
  if (!error.response) {
    return ERROR_TYPES.NETWORK
  }

  const { status } = error.response
  
  if (status === 422) return ERROR_TYPES.VALIDATION
  if (status === 401 || status === 403) return ERROR_TYPES.AUTH
  if (status === 404) return ERROR_TYPES.NOT_FOUND
  if (status >= 500) return ERROR_TYPES.SERVER
  
  return ERROR_TYPES.UNKNOWN
}

export const getErrorMessage = (error) => {
  const type = getErrorType(error)
  const defaultMessages = {
    [ERROR_TYPES.VALIDATION]: 'Please check your input and try again',
    [ERROR_TYPES.AUTH]: 'You are not authorized to perform this action',
    [ERROR_TYPES.NOT_FOUND]: 'The requested resource was not found',
    [ERROR_TYPES.SERVER]: 'Something went wrong on our end',
    [ERROR_TYPES.NETWORK]: 'Unable to connect to the server',
    [ERROR_TYPES.UNKNOWN]: 'An unexpected error occurred'
  }

  if (error.response?.data?.message) {
    return error.response.data.message
  }

  return defaultMessages[type]
}

export function handleApiError(error) {
  const router = useRouter()
  const toast = useToast()
  const status = error?.response?.status

  switch (status) {
    case 500:
      // Clear current history state and replace with home
      window.history.replaceState(null, '', '/')
      router.replace({ name: 'home' }).then(() => {
        // Add listener for back button
        window.addEventListener('popstate', () => {
          router.push({ name: 'home' })
        }, { once: true })
      })
      break
    case 400:
    case 401:
    case 403:
    case 404:
    case 422:
      router.push({ name: 'home' })
      break
    default:
      toast.error('Something went wrong. Please try again.')
      router.push({ name: 'home' })
  }
}