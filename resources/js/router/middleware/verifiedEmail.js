import { useAuthStore } from '@/stores/auth'

export default function verifiedEmail(to, from, next) {
  const auth = useAuthStore()
  
  if (to.meta.requiresVerification && !auth.isVerified) {
    // Allow access to verification page and support
    if (to.name === 'verification.notice' || to.name === 'support') {
      return next()
    }
    
    return next({ name: 'verification.notice' })
  }
  
  next()
}