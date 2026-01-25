<script setup>
import { ref, computed } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3'
import ChangePassword from './ChangePassword.vue'
import ManageSubscription from './ManageSubscription.vue'
import DeleteUserForm from '../Partials/DeleteUserForm.vue'
import { avatarList } from '@/Data/avatarList'
import PixelAvatar from '@/Components/PixelAvatar.vue'
import { trans } from 'laravel-vue-i18n';

const props = defineProps({
  user: Object,
})

const page = usePage()
const isPremium = computed(() => page.props.auth.user?.is_premium || props.user.is_premium)

const successMessage = ref('')

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  avatar: props.user.profile_photo_path ?? null,
})

const allAvatars = ref(avatarList)

const selectAvatar = (avatar) => {
  if (avatar.premium && !isPremium.value) {
    if(confirm(trans("Aquest avatar és exclusiu per a membres Premium 👑. Vols fer-te Premium?"))) {
        router.visit('/premium')
    }
    return
  }
  
  form.avatar = avatar.id
}

const submit = () => {
  form.patch(route('profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      successMessage.value = trans('✅ Canvis guardats correctament.')
      setTimeout(() => (successMessage.value = ''), 3000)
    },
  })
}

const handlePasswordUpdated = () => {
  successMessage.value = trans('🔐 Contrasenya actualitzada correctament.')
  setTimeout(() => (successMessage.value = ''), 3000)
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
}
.scrollbar-thin::-webkit-scrollbar-track {
  background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 20px;
}
.dark .scrollbar-thin::-webkit-scrollbar-thumb {
  background-color: #475569;
}
</style>

<template>
  <div
    class="bg-white dark:bg-gray-900 shadow-md rounded-xl p-6 space-y-6 border border-gray-100 dark:border-gray-800 transition-colors duration-300"
  >
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">👤 {{ $t('Informació del compte') }}</h2>

    <transition name="fade">
      <div
        v-if="successMessage"
        class="p-3 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded text-sm border border-green-200 dark:border-green-800 mb-4"
      >
        {{ successMessage }}
      </div>
    </transition>

    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $t("Nom d'usuari") }}</label>
      
      <input
        v-model="form.name"
        type="text"
        maxlength="30"
        class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition"
      />
      
      <div class="flex justify-between mt-1">
        <div class="text-red-600 text-sm">
            <span v-if="form.errors.name">{{ form.errors.name }}</span>
        </div>
        
        <div class="text-xs" :class="form.name.length >= 30 ? 'text-red-500 font-bold' : 'text-gray-500'">
            {{ form.name.length }}/30
        </div>
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $t('Correu electrònic') }}</label>
      <input
        v-model="form.email"
        type="email"
        disabled
        class="mt-1 w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-gray-600 dark:text-gray-400 cursor-not-allowed truncate"
      />
    </div>

    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">{{ $t('Tria el teu avatar') }}</h3>
      
      <div class="flex flex-wrap justify-center gap-3 max-h-[500px] overflow-y-auto p-3 border border-gray-100 dark:border-gray-800 rounded-xl bg-gray-50/50 dark:bg-gray-900/50 scrollbar-thin">
        
        <button
          v-for="avatar in allAvatars"
          :key="avatar.id"
          type="button"
          @click="selectAvatar(avatar)"
          :class="[
            'relative w-20 h-20 rounded-xl overflow-hidden transition-all duration-200 ease-in-out group flex flex-col items-center justify-center bg-white dark:bg-gray-800 border-2',
            'focus:outline-none',
            form.avatar === avatar.id
              ? 'border-blue-500 dark:border-red-500 scale-105 z-10 shadow-lg ring-2 ring-blue-500/30'
              : 'border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500',
            (avatar.premium && !isPremium) ? 'cursor-not-allowed opacity-60 grayscale' : 'cursor-pointer'
          ]"
        >
          <div class="w-12 h-12">
            <PixelAvatar :id="avatar.id" />
          </div>

          <span class="text-[9px] font-bold text-gray-500 mt-1 uppercase truncate w-full text-center px-1">{{ avatar.name }}</span>

          <div 
            v-if="avatar.premium && !isPremium" 
            class="absolute inset-0 bg-gray-900/60 flex items-center justify-center z-20 backdrop-blur-[1px]"
          >
            <span class="text-xl">🔒</span>
          </div>
          
        </button>
        
        <button
          type="button"
          @click="form.avatar = null"
          :class="[
            'w-20 h-20 rounded-xl overflow-hidden transition-all duration-200 ease-in-out bg-red-50 dark:bg-red-900/20 flex flex-col items-center justify-center border-2',
            'focus:outline-none focus:ring-4 focus:ring-offset-2 dark:focus:ring-offset-gray-900',
            form.avatar === null
              ? 'border-red-500 ring-2 ring-red-500/30'
              : 'border-transparent hover:border-red-300'
          ]"
          :title="$t('Eliminar avatar')"
        >
          <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
          <span class="text-[9px] font-bold text-red-400 mt-1 uppercase">{{ $t('Eliminar') }}</span>
        </button>

      </div>
      
      <p v-if="!isPremium" class="text-xs text-gray-500 mt-3 flex items-center gap-1 justify-center">
        {{ $t('Els avatars amb cadenat són exclusius per a membres') }} <span class="bg-gradient-to-r from-blue-600 to-red-600 text-white text-[9px] px-1.5 py-0.5 rounded font-bold">Premium</span>.
      </p>

      <div v-if="form.errors.avatar" class="text-red-600 text-sm mt-1">{{ form.errors.avatar }}</div>
    </div>

    <div class="pt-4 flex justify-end">
      <button
        @click="submit"
        :disabled="form.processing"
        class="px-4 py-2 rounded-lg font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-red-600 dark:hover:bg-red-700 transition disabled:opacity-50 shadow-sm"
      >
        {{ form.processing ? $t('Guardant...') : $t('💾 Guardar canvis') }}
      </button>
    </div>

    <ChangePassword @password-updated="handlePasswordUpdated" />

    <ManageSubscription v-if="isPremium" />

    <DeleteUserForm />

  </div>
</template>