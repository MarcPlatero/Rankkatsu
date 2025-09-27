<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow p-4 flex justify-between">
      <div class="text-xl font-bold">RankDuel</div>
      <div class="flex gap-4">
        <Link href="/">Home</Link>
        <Link href="/rankings">Rankings</Link>
        <Link href="/about">About</Link>
      </div>
    </nav>

    <main class="p-6">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
