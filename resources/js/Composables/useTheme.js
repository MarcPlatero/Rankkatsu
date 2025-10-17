import { ref, onMounted } from 'vue'

const isDarkMode = ref(false)

export function useTheme() {
  onMounted(() => {
    const storedTheme = localStorage.getItem('theme')
    if (storedTheme) {
      isDarkMode.value = storedTheme === 'dark'
    } else {
      isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches
    }
    applyTheme()
  })

  function toggleDarkMode() {
    isDarkMode.value = !isDarkMode.value
    localStorage.setItem('theme', isDarkMode.value ? 'dark' : 'light')
    applyTheme()
  }

  function applyTheme() {
    if (isDarkMode.value) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  }

  return { isDarkMode, toggleDarkMode }
}
