<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted } from 'vue'
import PixelAvatar from '@/Components/PixelAvatar.vue'

const page = usePage()
const user = computed(() => page.props.auth.user)

const notificationsOpen = ref(false)
const notifications = computed(() => page.props.auth.user?.notifications || [])
const unreadCount = computed(() => page.props.auth.user?.unread_count || 0)

const markAsRead = () => {
  notificationsOpen.value = !notificationsOpen.value
  
  if (notificationsOpen.value && unreadCount.value > 0) {
    router.post('/notifications/mark-read', {}, { preserveScroll: true })
  }
}

const closeNotifications = (e) => {
  if (!e.target.closest('#notification-btn') && !e.target.closest('#notification-dropdown')) {
    notificationsOpen.value = false
  }
}

const isDropdownOpen = ref(false)

const logout = () => {
  router.post('/logout', {}, {
    onSuccess: () => router.visit('/'),
  })
}

const closeDropdown = (e) => {
  if (!e.target.closest('#user-menu-btn') && !e.target.closest('#user-menu-dropdown')) {
    isDropdownOpen.value = false
  }
}

const isPixelAvatar = (path) => {
  return path && typeof path === 'string' && path.startsWith('pixel-')
}

onMounted(() => {
  window.addEventListener('click', closeDropdown)
  window.addEventListener('click', closeNotifications)
})

onUnmounted(() => {
  window.removeEventListener('click', closeDropdown)
  window.removeEventListener('click', closeNotifications)
})
</script>

<template>
  <nav
    class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 transition-colors duration-300"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
      
      <Link href="/" class="text-2xl font-black tracking-tighter flex items-center gap-1 shrink-0 mr-4">
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Rank</span>
        <span class="text-gray-300 dark:text-gray-600 text-xl">|</span>
        <span class="text-transparent bg-clip-text bg-gradient-to-l from-red-600 to-pink-500">katsu</span>
      </Link>

      <div class="hidden md:flex items-center space-x-5 text-sm font-medium">
        <Link href="/" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Home</Link>
        <Link href="/profile" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">Perfil</Link>
        <Link href="/rankings" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Rankings</Link>
        <Link href="/rankings/create" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">Create</Link>
        <Link href="/settings" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Configuració</Link>
        <Link href="/about" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">About</Link>
        
        <Link href="/premium" class="text-gray-700 dark:text-gray-300 hover:text-yellow-500 dark:hover:text-yellow-400 transition flex items-center gap-1 group">
          <span>Premium</span>
          <span class="group-hover:scale-125 transition-transform duration-300">👑</span>
        </Link>
      </div>

      <div class="flex items-center gap-4">
        
        <template v-if="!user">
          <div class="flex items-center gap-3">
            <Link href="/register" class="px-4 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg transition shadow-sm">
                Register
            </Link>
            <Link href="/login" class="px-4 py-2 text-sm font-bold text-white bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 rounded-lg transition shadow-sm">
                Login
            </Link>
          </div>
        </template>

        <template v-else>
          <div class="relative mr-2">
            <button 
                id="notification-btn"
                @click="markAsRead" 
                class="relative p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition focus:outline-none"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                
                <span v-if="unreadCount > 0" class="absolute top-1.5 right-1.5 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 text-[8px] text-white font-bold items-center justify-center"></span>
                </span>
            </button>

            <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <div 
                    v-if="notificationsOpen"
                    id="notification-dropdown"
                    class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50 overflow-hidden"
                >
                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 font-bold text-gray-700 dark:text-gray-200 flex justify-between items-center">
                        <span>Notificacions</span>
                        <span v-if="unreadCount > 0" class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">{{ unreadCount }} noves</span>
                    </div>

                    <div v-if="notifications.length === 0" class="px-4 py-6 text-center text-gray-500 text-sm">
                        No tens notificacions recents. 💤
                    </div>

                    <div v-else class="max-h-80 overflow-y-auto">
                        <Link 
                            v-for="notification in notifications" 
                            :key="notification.id"
                            :href="`/rankings/${notification.data.ranking_id}`"
                            class="block px-4 py-3 transition border-b border-gray-100 dark:border-gray-700/50 last:border-0 relative group"
                            :class="[
                                !notification.read_at 
                                    ? 'bg-blue-50/80 dark:bg-blue-900/20' 
                                    : 'hover:bg-gray-50 dark:hover:bg-gray-700/50'
                            ]"
                        >
                            <div class="flex items-start gap-3">
                                
                                <span v-if="!notification.read_at" class="absolute top-4 right-3 w-2 h-2 rounded-full bg-blue-500"></span>

                                <div class="w-9 h-9 rounded-full overflow-hidden shrink-0 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                                    <PixelAvatar 
                                        v-if="isPixelAvatar(notification.data.user_avatar)" 
                                        :id="notification.data.user_avatar" 
                                        className="w-full h-full"
                                    />
                                    <img 
                                        v-else-if="notification.data.user_avatar_url" 
                                        :src="notification.data.user_avatar_url" 
                                        class="w-full h-full object-cover"
                                    >
                                    <span v-else class="w-full h-full flex items-center justify-center font-bold text-xs text-gray-500">
                                        {{ notification.data.user_name.charAt(0) }}
                                    </span>
                                </div>

                                <div class="text-sm pr-4">
                                    <p class="text-gray-800 dark:text-gray-200 leading-snug">
                                        <span class="font-bold">{{ notification.data.user_name }}</span>
                                        {{ notification.data.message }}
                                        <span class="font-medium text-blue-600 dark:text-red-400 block truncate max-w-[220px]">
                                            "{{ notification.data.ranking_title }}"
                                        </span>
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">{{ notification.created_at }}</p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </transition>
          </div>

          <div class="relative">
            
            <button 
              id="user-menu-btn"
              @click="isDropdownOpen = !isDropdownOpen"
              class="flex items-center gap-3 p-1 pr-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none focus:ring-2 focus:ring-blue-500/50 max-w-[200px] sm:max-w-xs cursor-pointer border border-transparent hover:border-gray-200 dark:hover:border-gray-700"
            >
              <div class="w-9 h-9 rounded-full overflow-hidden border border-gray-200 dark:border-gray-600 shrink-0 bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                
                <PixelAvatar 
                  v-if="isPixelAvatar(user.profile_photo_path)" 
                  :id="user.profile_photo_path" 
                  className="w-full h-full"
                />

                <img 
                  v-else-if="user.profile_photo_url" 
                  :src="user.profile_photo_url" 
                  alt="Avatar" 
                  class="w-full h-full object-cover"
                >

                <div v-else class="text-blue-600 dark:text-blue-300 font-bold text-sm">
                  {{ user.name.charAt(0).toUpperCase() }}
                </div>

              </div>

              <div class="flex flex-col items-start truncate hidden sm:flex">
                <div class="flex items-center gap-1.5 w-full">
                  <span class="text-sm font-semibold text-gray-700 dark:text-gray-200 truncate max-w-[100px]" :title="user.name">
                    {{ user.name }}
                  </span>
                  
                  <span v-if="user.is_premium" class="shrink-0 px-1.5 py-[1px] rounded text-[9px] font-bold bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-sm uppercase leading-none">
                    Premium
                  </span>
                </div>
              </div>

              <svg :class="{'rotate-180': isDropdownOpen}" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div 
                v-if="isDropdownOpen"
                id="user-menu-dropdown"
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 py-1 z-50 overflow-hidden"
              >
                <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 sm:hidden">
                    <p class="text-xs text-gray-500 truncate">{{ user.email }}</p>
                </div>

                <Link 
                  v-if="user.is_admin" 
                  href="/admin/moderation" 
                  class="block px-4 py-2 text-sm font-medium transition flex items-center gap-2 text-blue-600 hover:bg-blue-50 dark:text-red-400 dark:hover:bg-red-900/20"
                >
                  🛡️ Moderació
                </Link>

                <button 
                  @click="logout" 
                  class="w-full text-left px-4 py-2 text-sm font-semibold transition flex items-center gap-2 text-blue-600 hover:bg-blue-50 dark:text-red-400 dark:hover:bg-red-900/20"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                  Tancar sessió
                </button>
              </div>
            </transition>

          </div>
        </template>

      </div>
    </div>
  </nav>
</template>