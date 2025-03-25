const setFavicon = () => {
  // Set main favicon
  const favicon = document.querySelector('link[rel="icon"]') || document.createElement('link')
  favicon.rel = 'icon'
  favicon.href = '/images/JVITTechs-Logo.png' // Use existing logo
  document.head.appendChild(favicon)
  
  // Also set Apple touch icon (using same logo)
  const touchIcon = document.querySelector('link[rel="apple-touch-icon"]') || document.createElement('link')
  touchIcon.rel = 'apple-touch-icon'
  touchIcon.href = '/images/JVITTechs-Logo.png' // Use existing logo
  document.head.appendChild(touchIcon)
}

export default setFavicon