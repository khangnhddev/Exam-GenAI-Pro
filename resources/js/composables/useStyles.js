import { levelColors } from '../constants/styles'

export function useStyles() {
  const getLevelClass = (level) => {
    const normalizedLevel = level?.toLowerCase() || 'beginner'
    return {
      [levelColors[normalizedLevel].bg]: true,
      [levelColors[normalizedLevel].text]: true
    }
  }

  return {
    getLevelClass
  }
}