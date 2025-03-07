const setFavicon = () => {
  const favicon = document.querySelector('link[rel="icon"]') || document.createElement('link')
  favicon.type = 'image/svg+xml'
  favicon.rel = 'icon'
  favicon.href = `data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
    <defs>
      <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%">
        <stop offset="0%" style="stop-color:%234F46E5" />
        <stop offset="100%" style="stop-color:%23A855F7" />
      </linearGradient>
    </defs>
    <rect width="24" height="24" rx="6" fill="url(%23grad)"/>
    <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
  </svg>`
  document.head.appendChild(favicon)
}

export default setFavicon