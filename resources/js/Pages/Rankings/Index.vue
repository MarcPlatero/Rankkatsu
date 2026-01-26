<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import FavoriteStar from '@/Components/FavoriteStar.vue'
import AdBanner from '@/Components/AdBanner.vue'
import PixelAvatar from '@/Components/PixelAvatar.vue'
import { trans } from 'laravel-vue-i18n';

const props = defineProps({
  rankings: Object,
  filters: Object,
})

const page = usePage()
const flash = page.props.flash || {}

const search = ref(props.filters?.search || '')
const sort = ref(props.filters?.sort || 'popular')

const listRankings = ref(props.rankings.data)
const isLoadingMore = ref(false)

// Funció per aplicar filtres
function applyFilters() {
  router.get(
    '/rankings',
    { 
      search: search.value,
      sort: sort.value
    },
    { 
      preserveState: true, 
      replace: true,
      onSuccess: () => {
        listRankings.value = props.rankings.data
      }
    }
  )
}

// Funció de "Cargar més"
function loadMore() {
  if (!props.rankings.next_page_url) return;

  isLoadingMore.value = true;

  router.get(props.rankings.next_page_url, {}, {
    preserveState: true,
    preserveScroll: true,
    only: ['rankings'],
    onSuccess: () => {
      listRankings.value = [...listRankings.value, ...props.rankings.data];
      isLoadingMore.value = false;
    }
  })
}

watch(sort, () => {
  applyFilters()
})

function goToRanking(id) {
  router.get(`/rankings/${id}`)
}

const isPixelAvatar = (path) => {
  return path && typeof path === 'string' && path.startsWith('pixel-')
}

// Funció per calcular temps
const timeAgo = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    // Si fa menys d'un minut
    if (seconds < 60) return trans('Just now');

    let interval = Math.floor(seconds / 31536000);
    if (interval >= 1) {
        return interval === 1 
            ? trans('1 year ago') 
            : trans(':count years ago', { count: interval });
    }
    
    interval = Math.floor(seconds / 2592000);
    if (interval >= 1) {
        return interval === 1 
            ? trans('1 month ago') 
            : trans(':count months ago', { count: interval });
    }
    
    interval = Math.floor(seconds / 86400);
    if (interval >= 1) {
        return interval === 1 
            ? trans('1 day ago') 
            : trans(':count days ago', { count: interval });
    }
    
    interval = Math.floor(seconds / 3600);
    if (interval >= 1) {
        return interval === 1 
            ? trans('1 hour ago') 
            : trans(':count hours ago', { count: interval });
    }
    
    interval = Math.floor(seconds / 60);
    if (interval >= 1) {
        return interval === 1 
            ? trans('1 minute ago') 
            : trans(':count minutes ago', { count: interval });
    }
    
    return trans('Just now');
}

// Funció helper per als vots
const votesText = (count) => {
    if (!count) count = 0;
    if (count === 1) return trans('1 vote/24h');
    return trans(':count votes/24h', { count: count });
}
</script>

<style scoped>
@keyframes gradient-slow {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.animate-gradient-slow {
  animation: gradient-slow 8s ease infinite;
}
.line-clamp-2 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  word-break: break-word; 
  line-height: 1.4em;
  height: 2.8em;
}
.line-clamp-3 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  word-break: break-word; 
  line-height: 1.4em;
  height: 4.2em;
}
</style>

<template>
  <AppLayout>
    <div
      class="w-full mx-auto p-6 min-h-screen transition-colors duration-300 
             bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
    >
      <h1 class="text-3xl font-extrabold mb-10 text-center">
        🔍 {{ $t('Tots els rànquings') }}
      </h1>

      <div
        v-if="flash.success"
        class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100 shadow"
      >
        {{ flash.success }}
      </div>
      <div
        v-if="flash.error"
        class="mb-4 p-4 rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100 shadow"
      >
        {{ flash.error }}
      </div>

      <div class="flex flex-col gap-6 mb-12 max-w-6xl mx-auto">
        
        <div class="relative group max-w-2xl mx-auto w-full">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-red-400 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-500"></div>
            <div class="relative flex w-full shadow-lg rounded-2xl bg-white dark:bg-gray-800 overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="pl-4 flex items-center justify-center text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input
                    v-model="search"
                    @keyup.enter="applyFilters"
                    type="text"
                    :placeholder="$t('Cerca rànquings o opcions...')"
                    class="w-full px-4 py-4 bg-transparent border-none focus:ring-0 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 text-lg focus:outline-none"
                />
                <button
                    @click="applyFilters"
                    class="px-8 font-semibold bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors border-l border-gray-100 dark:border-gray-700"
                >
                    {{ $t('Cercar') }}
                </button>
            </div>
        </div>

        <div class="flex justify-center">
            <div class="flex p-1 space-x-2 bg-gray-100 dark:bg-gray-800 rounded-xl overflow-x-auto shadow-inner border border-gray-200 dark:border-gray-700">
                <button
                    @click="sort = 'trending'"
                    :class="[
                        'px-4 sm:px-6 py-2 text-sm font-bold rounded-lg transition-all duration-200 whitespace-nowrap',
                        sort === 'trending' 
                            ? 'bg-white dark:bg-gray-700 text-red-600 dark:text-red-400 shadow-sm ring-1 ring-black/5 dark:ring-white/10' 
                            : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'
                    ]"
                >
                    🔥 {{ $t('Tendència') }}
                </button>
                <button
                    @click="sort = 'popular'"
                    :class="[
                        'px-4 sm:px-6 py-2 text-sm font-bold rounded-lg transition-all duration-200 whitespace-nowrap',
                        sort === 'popular' 
                            ? 'bg-white dark:bg-gray-700 text-yellow-600 dark:text-yellow-400 shadow-sm ring-1 ring-black/5 dark:ring-white/10' 
                            : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'
                    ]"
                >
                    🏆 {{ $t('Més Populars') }}
                </button>
                <button
                    @click="sort = 'recent'"
                    :class="[
                        'px-4 sm:px-6 py-2 text-sm font-bold rounded-lg transition-all duration-200 whitespace-nowrap',
                        sort === 'recent' 
                            ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-sm ring-1 ring-black/5 dark:ring-white/10' 
                            : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'
                    ]"
                >
                    🆕 {{ $t('Novetats') }}
                </button>
            </div>
        </div>
      </div>
      
      <div class="max-w-4xl mx-auto mb-8">
        <AdBanner format="horizontal" />
      </div>

      <div class="text-center mb-12">
        <Link
          href="/rankings/create"
          class="relative inline-block px-7 py-3 rounded-xl font-semibold text-white text-lg shadow-lg overflow-hidden group hover:scale-105 transition-transform duration-300"
        >
          <span
            class="absolute inset-0 bg-gradient-to-r from-blue-600 via-red-500 to-blue-600
                   animate-gradient-slow bg-[length:200%_200%] transition-all duration-500"
          ></span>
          <span class="relative z-10 flex items-center justify-center gap-2">🚀 {{ $t('Crear nou rànquing') }}</span>
        </Link>
      </div>

      <div
        v-if="!listRankings || listRankings.length === 0"
        class="text-gray-600 dark:text-gray-400 mt-8 text-center text-lg py-12"
      >
        <div class="text-4xl mb-3">😕</div>
        {{ $t('No s’han trobat rànquings amb aquests filtres.') }}
      </div>

      <div
        v-else
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 
               gap-8 w-full px-4"
      >
        <div
          v-for="ranking in listRankings"
          :key="ranking.id"
          class="relative flex flex-col rounded-2xl border border-gray-200 dark:border-gray-700 
                 bg-white dark:bg-neutral-900 shadow-sm hover:shadow-xl hover:-translate-y-1
                 transition-all duration-300 overflow-hidden group cursor-pointer"
          @click="goToRanking(ranking.id)"
        >
          <div
            class="w-full aspect-video overflow-hidden flex items-center justify-center 
                   bg-gray-100 dark:bg-gray-800 relative"
          >
            <img
              v-if="ranking.image"
              :src="`/storage/${ranking.image}`"
              alt="Imatge del rànquing"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
            <div v-else class="text-5xl text-gray-400 dark:text-gray-500">
              🗂️
            </div>

            <div v-if="sort === 'recent'" class="absolute top-2 left-2 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 shadow-sm z-10">
                <span>🕒</span> {{ timeAgo(ranking.created_at) }}
            </div>

            <div v-if="sort === 'trending'" class="absolute top-2 left-2 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 shadow-sm z-10">
                <span class="text-green-500">▲</span> {{ votesText(ranking.recent_votes_count) }}
            </div>
          </div>

          <div class="flex-1 p-4 relative flex flex-col justify-between">
            <div @click.stop>
              <FavoriteStar :ranking="ranking" class="absolute top-3 right-3 z-10" />
            </div>

            <div class="pr-8">
              <h2
                class="text-lg font-bold mb-2 text-gray-900 dark:text-white 
                       break-words group-hover:text-blue-600 dark:group-hover:text-red-400 
                       transition-colors duration-200 line-clamp-2"
              >
                {{ ranking.title }}
              </h2>

              <p
                class="text-sm text-gray-700 dark:text-gray-300 whitespace-normal break-words line-clamp-3 mb-2"
              >
                {{ ranking.description }}
              </p>

              <ul
                class="text-xs text-gray-600 dark:text-gray-400 whitespace-normal break-words mb-4"
              >
                <li
                  v-for="opt in ranking.options.slice(0, 2)"
                  :key="opt.id"
                  class="leading-snug"
                >
                  • {{ opt.name }}
                </li>
                <li
                  v-if="ranking.options.length > 2"
                  class="text-gray-400 italic mt-1"
                >
                  +{{ ranking.options.length - 2 }} {{ $t('més...') }}
                </li>
              </ul>

              <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-4">
                <div class="w-6 h-6 rounded-full overflow-hidden border border-gray-300 dark:border-gray-600 flex items-center justify-center flex-shrink-0 bg-gray-100 dark:bg-gray-800">
                    <PixelAvatar 
                        v-if="isPixelAvatar(ranking.user?.profile_photo_path)" 
                        :id="ranking.user.profile_photo_path" 
                        className="w-full h-full" 
                    />
                    <img 
                        v-else-if="ranking.user?.profile_photo_url" 
                        :src="ranking.user.profile_photo_url" 
                        class="w-full h-full object-cover"
                    >
                    <span v-else class="text-[10px] font-bold text-gray-500">?</span>
                </div>
                
                <div class="flex items-center gap-1.5 min-w-0">
                    <span class="truncate font-medium">{{ ranking.user?.name || $t('Anònim') }}</span>
                    
                    <span 
                        v-if="ranking.user?.is_premium" 
                        class="flex-shrink-0 inline-flex items-center justify-center w-5 h-5 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-sm"
                        :title="$t('Usuari Premium')"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                          <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>

              </div>
              </div>

            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 text-right">
              <span
                class="text-blue-600 dark:text-red-400 font-semibold text-sm hover:underline"
              >
                {{ $t('Veure detalls') }} →
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="max-w-4xl mx-auto mt-12">
        <AdBanner format="horizontal" />
      </div>

      <div v-if="rankings.next_page_url" class="text-center mt-8 pb-10">
        <button
          @click="loadMore"
          :disabled="isLoadingMore"
          class="px-8 py-3 font-semibold rounded-full shadow-md text-white
                 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed
                 bg-blue-600 hover:bg-blue-700 
                 dark:bg-red-600 dark:hover:bg-red-700"
        >
          <span v-if="isLoadingMore">{{ $t('Carregant...') }}</span>
          <span v-else>{{ $t('Carregar més rankings') }}</span>
        </button>
      </div>

    </div>
  </AppLayout>
</template>