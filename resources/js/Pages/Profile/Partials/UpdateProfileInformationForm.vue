<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import PixelAvatar from '@/Components/PixelAvatar.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    avatar: user.profile_photo_path || 'pixel-crown',
});

const selectAvatar = (id) => {
    form.avatar = id;
    form.isDirty = true;
};

const avatars = [
  { id: 'pixel-swords', name: 'Duelist' },
  { id: 'pixel-bowl', name: 'Katsu Bowl' },
  { id: 'pixel-slime-blue', name: 'Blue Team' },
  { id: 'pixel-slime-red', name: 'Red Team' },
  { id: 'pixel-ninja', name: 'Ninja' }, 
  { id: 'pixel-pirate', name: 'Pirate' }, 
  { id: 'pixel-orb', name: 'Dragon Orb' }, 
  { id: 'pixel-soccer', name: 'Striker' }, 
  { id: 'pixel-hero', name: 'Hero' }, 
  { id: 'pixel-heart', name: 'Life' },
  { id: 'pixel-ghost', name: 'Ghost' },
  { id: 'pixel-invader', name: 'Invader' },
  { id: 'pixel-shroom', name: 'Shroom' },
  { id: 'pixel-gem', name: 'Gem' },
  { id: 'pixel-ring', name: 'Ring' },
  { id: 'pixel-shield', name: 'Shield' },
  { id: 'pixel-potion', name: 'Potion' },
  { id: 'pixel-scroll', name: 'Scroll' },
  { id: 'pixel-cat', name: 'Cat' },
  { id: 'pixel-fox', name: 'Fox' },
  { id: 'pixel-panda', name: 'Panda' },
  { id: 'pixel-rocket', name: 'Rocket' },
  { id: 'pixel-burger', name: 'Burger' },
  { id: 'pixel-headset', name: 'Headset' },
  { id: 'pixel-crown', name: 'King', premium: true },
  { id: 'pixel-gamepad', name: 'Gamer', premium: true },
  { id: 'pixel-skull', name: 'Skull', premium: true },
  { id: 'pixel-mask', name: 'Mask', premium: true },
  { id: 'pixel-tiara', name: 'Tiara', premium: true },
  { id: 'pixel-mecha', name: 'Mecha', premium: true },
  { id: 'pixel-titan', name: 'Titan', premium: true },
  { id: 'pixel-watch', name: 'Watch', premium: true },
  { id: 'pixel-wizard', name: 'Wizard', premium: true },
  { id: 'pixel-chest', name: 'Loot', premium: true },
  { id: 'pixel-rankball', name: 'Rankball', premium: true },
  { id: 'pixel-pickaxe', name: 'Pickaxe', premium: true },
  { id: 'pixel-star', name: 'Star', premium: true },
  { id: 'pixel-helmet', name: 'Helmet', premium: true },
  { id: 'pixel-axe', name: 'Axe', premium: true },
  { id: 'pixel-castle', name: 'Castle', premium: true },
  { id: 'pixel-lion', name: 'Lion', premium: true },
  { id: 'pixel-wolf', name: 'Wolf', premium: true },
  { id: 'pixel-dragon', name: 'Dragon', premium: true },
  { id: 'pixel-bolt', name: 'Bolt', premium: true },
];
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 20px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #475569;
}
</style>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Informació del Perfil
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Actualitza la informació del teu compte, el correu i tria el teu avatar.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 space-y-6"
        >
            
            <div>
                <InputLabel value="Tria el teu Avatar" />
                
                <div class="mt-4 flex flex-col gap-4">
                    <div class="flex justify-center mb-2">
                        <div class="w-24 h-24 rounded-full border-4 border-blue-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-1 shadow-md">
                            <PixelAvatar :id="form.avatar" />
                        </div>
                    </div>

                    <div class="grid grid-cols-5 sm:grid-cols-8 gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 max-h-64 overflow-y-auto custom-scrollbar">
                        <button 
                            v-for="avatar in avatars" 
                            :key="avatar.id"
                            type="button"
                            @click="selectAvatar(avatar.id)"
                            :class="[
                                'relative group w-full aspect-square rounded-lg p-1 transition-all duration-200 border-2',
                                form.avatar === avatar.id 
                                    ? 'border-blue-500 bg-blue-100 dark:bg-blue-900/30 shadow-md ring-2 ring-blue-200 dark:ring-blue-800 scale-110 z-10' 
                                    : 'border-transparent hover:bg-white dark:hover:bg-gray-700 hover:shadow-sm'
                            ]"
                            :title="avatar.name"
                        >
                            <PixelAvatar :id="avatar.id" />
                            
                            <span v-if="avatar.premium" class="absolute -top-1 -right-1 text-[10px]">👑</span>
                        </button>
                    </div>
                    <p class="text-xs text-center text-gray-500">Fes clic en una icona per seleccionar-la.</p>
                </div>
            </div>

            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>