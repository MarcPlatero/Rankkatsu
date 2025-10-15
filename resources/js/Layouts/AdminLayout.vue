<script setup>
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user
</script>

<template>
  <nav>
    <Link href="/">Inici</Link>
    <Link href="/rankings">Rankings</Link>

    <!-- Només mostrar si és admin -->
    <template v-if="user?.is_admin">
      <Link href="/admin/moderation">Moderació</Link>
    </template>
  </nav>
</template>
