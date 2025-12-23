<script setup>
defineProps({
  id: {
    type: String,
    required: true
  },
  className: {
    type: String,
    default: 'w-full h-full'
  }
})
</script>

<style scoped>
.pixel-art {
  shape-rendering: crispEdges;
}
</style>

<template>
  <svg :class="['pixel-art', className]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
    
    <g v-if="id === 'pixel-crown'"><path d="M2 6H4V4H6V6H8V8H10V6H12V4H14V6H16V8H18V6H20V4H22V6H24V18H2V6Z" fill="#1e293b"/><path d="M4 6H6V8H4V6ZM8 8H6V10H4V16H20V10H18V8H16V10H14V8H12V10H10V8H8ZM18 6H20V8H18V6ZM12 6H14V8H12V6Z" fill="#fbbf24"/><path d="M6 12H8V14H6V12Z" fill="#3b82f6"/><path d="M16 12H18V14H16V12Z" fill="#ef4444"/><path d="M11 12H13V14H11V12Z" fill="#ffffff"/></g>
    <g v-else-if="id === 'pixel-swords'"><path d="M5 3H7V5H9V7H11V9H13V11H11V13H9V15H7V17H5V19H3V17H5V15H7V13H9V11H7V9H5V7H3V5H5V3Z" fill="#1d4ed8"/><path d="M7 5H9V7H11V9H13V11H11V9H9V7H7V5Z" fill="#60a5fa"/><path d="M3 17H5V19H7V21H5V19H3V17Z" fill="#94a3b8"/><path d="M19 3H17V5H15V7H13V9H15V11H17V13H19V15H21V13H19V11H17V9H19V7H21V5H19V3Z" fill="#b91c1c"/><path d="M17 5H15V7H13V9H15V7H17V5Z" fill="#f87171"/><path d="M21 17H19V19H17V21H19V19H21V17Z" fill="#94a3b8"/></g>
    <g v-else-if="id === 'pixel-bowl'"><path d="M4 14H20V16H18V18H6V16H4V14Z" fill="#be123c"/><path d="M6 16H18V17H6V16Z" fill="#1d4ed8"/><path d="M4 12H20V14H4V12Z" fill="#f8fafc"/><path d="M6 8H18V12H6V8Z" fill="#d97706"/><path d="M8 10H16V11H8V10Z" fill="#fcd34d"/><path d="M9 9H11V10H9V9ZM13 9H15V10H13V9Z" fill="#92400e"/><path d="M10 4H12V6H10V4ZM14 5H16V7H14V5Z" fill="#e2e8f0"/></g>
    <g v-else-if="id === 'pixel-slime-blue'"><path d="M8 6H16V8H18V10H20V16H18V18H6V16H4V10H6V8H8V6Z" fill="#1e3a8a"/><path d="M8 8H16V10H18V16H16V14H8V16H6V10H8V8Z" fill="#3b82f6"/><path d="M10 8H12V10H10V8ZM8 10H10V12H8V10Z" fill="#93c5fd"/><path d="M9 12H11V14H9V12ZM13 12H15V14H13V12Z" fill="#1e293b"/></g>
    <g v-else-if="id === 'pixel-slime-red'"><path d="M8 6H16V8H18V10H20V16H18V18H6V16H4V10H6V8H8V6Z" fill="#7f1d1d"/><path d="M8 8H16V10H18V16H16V14H8V16H6V10H8V8Z" fill="#ef4444"/><path d="M10 8H12V10H10V8ZM8 10H10V12H8V10Z" fill="#fca5a5"/><path d="M9 12H11V14H9V12ZM13 12H15V14H13V12Z" fill="#1e293b"/></g>
    <g v-else-if="id === 'pixel-gamepad'"><path d="M4 8H20V16H4V8Z" fill="#64748b"/><path d="M4 16H20V17H4V16Z" fill="#334155"/><path d="M7 10H9V11H10V13H9V14H7V13H6V11H7V10Z" fill="#0f172a"/><path d="M16 11H18V13H16V11Z" fill="#ef4444"/><path d="M13 12H15V14H13V12Z" fill="#3b82f6"/><path d="M10 14H12V15H10V14ZM13 14H15V15H13V14Z" fill="#334155"/></g>

    <g v-else-if="id === 'pixel-ninja'"><path d="M4 6H20V12H22V16H20V12H18V14H16V12H14V14H12V12H10V14H8V12H6V14H4V12H2V16H4V12H4V6Z" fill="#1e293b"/><path d="M6 8H18V12H6V8Z" fill="#94a3b8"/><path d="M6 8H8V12H6V8ZM16 8H18V12H16V8Z" fill="#cbd5e1"/><path d="M2 12H4V16H2V12ZM20 12H22V16H20V12Z" fill="#334155"/></g>
    <g v-else-if="id === 'pixel-pirate'"><path d="M8 4H16V8H8V4Z" fill="#facc15"/><path d="M8 8H16V10H8V8Z" fill="#dc2626"/><path d="M2 10H22V12H2V10Z" fill="#facc15"/><path d="M4 12H20V13H4V12Z" fill="#eab308"/></g>
    <g v-else-if="id === 'pixel-orb'"><path d="M8 4H16V6H18V8H20V16H18V18H16V20H8V18H6V16H4V8H6V6H8V4Z" fill="#f97316"/><path d="M10 6H12V8H10V6ZM14 8H16V10H14V8ZM8 12H10V14H8V12ZM14 14H16V16H14V14Z" fill="#dc2626"/><path d="M6 8H8V10H6V8Z" fill="#fbbf24"/></g>
    <g v-else-if="id === 'pixel-soccer'"><path d="M8 4H16V6H18V8H20V16H18V18H16V20H8V18H6V16H4V8H6V6H8V4Z" fill="#f8fafc"/><path d="M6 8H8V10H6V8ZM16 8H18V10H16V8Z" fill="#1e293b"/><path d="M8 12H12V14H10V16H8V18H6V14H8V12Z" fill="#facc15"/><path d="M12 8H16V10H14V12H12V8Z" fill="#eab308"/><path d="M10 18H14V20H10V18Z" fill="#1e293b"/></g>
    <g v-else-if="id === 'pixel-hero'"><path d="M6 4H8V10H6V4ZM16 4H18V10H16V4Z" fill="#facc15"/><path d="M8 8H16V14H18V18H16V20H8V18H6V14H8V8Z" fill="#1d4ed8"/><path d="M10 14H14V16H10V14Z" fill="#ffffff"/><path d="M8 12H10V14H8V12ZM14 12H16V14H14V12Z" fill="#ffffff"/><path d="M10 2H14V4H10V2Z" fill="#1d4ed8"/></g>
    <g v-else-if="id === 'pixel-titan'"><path d="M6 4H18V8H20V20H18V18H16V20H14V18H12V20H10V18H8V20H6V18H4V20H6V8H4V20H2V8H4V6H6V4Z" fill="#15803d"/><path d="M8 8H16V14H8V8Z" fill="#1e293b"/><path d="M8 8H10V10H8V8ZM14 8H16V10H14V8Z" fill="#ffffff"/><path d="M10 10H14V12H10V10Z" fill="#3b82f6"/><path d="M10 12H14V14H10V12Z" fill="#ffffff"/></g>
    <g v-else-if="id === 'pixel-watch'"><path d="M10 2H14V6H10V2Z" fill="#94a3b8"/><path d="M8 6H16V8H18V16H16V18H8V16H6V8H8V6Z" fill="#cbd5e1"/><path d="M10 10H14V14H10V10Z" fill="#ef4444"/><path d="M8 10H6V14H8V10ZM16 10H18V14H16V10Z" fill="#94a3b8"/></g>
    <g v-else-if="id === 'pixel-mask'"><path d="M8 4H16V6H18V8H20V16H18V18H16V20H8V18H6V16H4V8H6V6H8V4Z" fill="#f8fafc"/><path d="M6 8H8V10H6V8ZM16 8H18V10H16V8Z" fill="#ef4444"/><path d="M8 12H10V14H8V12ZM14 12H16V14H14V12Z" fill="#1e293b"/><path d="M12 16H12.01V18H12V16Z" fill="#ef4444"/></g>
    <g v-else-if="id === 'pixel-tiara'"><path d="M4 10H20V14H18V12H14V14H10V12H6V14H4V10Z" fill="#facc15"/><path d="M11 8H13V12H11V8Z" fill="#dc2626"/><path d="M12 10H12.01V12H12V10Z" fill="#ef4444"/><path d="M10 8H4V10H10V8ZM14 8H20V10H14V8Z" fill="#eab308"/></g>
    <g v-else-if="id === 'pixel-mecha'"><path d="M8 4H16V18H14V20H10V18H8V4Z" fill="#5b21b6"/><path d="M10 6H14V16H10V6Z" fill="#a855f7"/><path d="M6 8H8V16H6V8ZM16 8H18V16H16V8Z" fill="#4c1d95"/><path d="M8 8H16V10H8V8Z" fill="#22c55e"/><path d="M10 18H14V20H10V18Z" fill="#eab308"/></g>

    <g v-else-if="id === 'pixel-heart'"><path d="M4 8H8V10H10V8H14V10H16V8H20V14H18V16H16V18H14V20H10V18H8V16H6V14H4V8Z" fill="#dc2626"/><path d="M6 10H8V12H6V10Z" fill="#fca5a5"/><path d="M8 10H10V12H8V10Z" fill="#991b1b"/></g>
    <g v-else-if="id === 'pixel-ghost'"><path d="M6 8H8V6H16V8H18V18H20V20H18V18H16V20H14V18H12V20H10V18H8V20H6V18H4V20H6V8Z" fill="#ef4444"/><path d="M6 10H10V14H6V10ZM14 10H18V14H14V10Z" fill="#ffffff"/><path d="M8 12H10V14H8V12ZM16 12H18V14H16V12Z" fill="#1e40af"/></g>
    <g v-else-if="id === 'pixel-invader'"><path d="M8 6H16V8H18V10H20V12H22V16H20V12H18V16H16V12H14V16H10V12H8V16H6V12H4V16H2V12H4V10H6V8H8V6Z" fill="#22c55e"/><path d="M8 10H10V12H8V10ZM14 10H16V12H14V10Z" fill="#1e293b"/></g>
    <g v-else-if="id === 'pixel-wizard'"><path d="M4 16H20V18H4V16Z" fill="#581c87"/><path d="M6 14H18V16H6V14Z" fill="#7e22ce"/><path d="M8 10H16V14H8V10Z" fill="#9333ea"/><path d="M10 6H14V10H10V6Z" fill="#a855f7"/><path d="M11 3H13V6H11V3Z" fill="#d8b4fe"/><path d="M12 8H14V10H12V8Z" fill="#fbbf24"/><path d="M7 14H9V16H7V14ZM16 12H18V14H16V12Z" fill="#facc15"/></g>
    <g v-else-if="id === 'pixel-skull'"><path d="M8 4H16V6H18V14H16V16H14V18H10V16H8V14H6V6H8V4Z" fill="#cbd5e1"/><path d="M8 8H10V10H8V8ZM14 8H16V10H14V8Z" fill="#1e293b"/><path d="M11 12H13V14H11V12Z" fill="#1e293b"/></g>
    <g v-else-if="id === 'pixel-chest'"><path d="M4 8H20V18H4V8Z" fill="#713f12"/><path d="M4 6H20V8H4V6Z" fill="#a16207"/><path d="M4 10H20V12H4V10Z" fill="#3f2305"/><path d="M11 10H13V14H11V10Z" fill="#fbbf24"/><path d="M4 8H6V18H4V8ZM18 8H20V18H18V8Z" fill="#451a03"/></g>

    <g v-else-if="id === 'pixel-rankball'"><path d="M6 4H18V6H20V8H22V16H20V18H18V20H6V18H4V16H2V8H4V6H6V4Z" fill="#1e293b"/><path d="M6 6H18V8H20V12H4V8H6V6Z" fill="#3b82f6"/><path d="M4 12H20V16H18V18H6V16H4V12Z" fill="#ef4444"/><path d="M4 11H20V13H4V11Z" fill="#1e293b"/><path d="M10 10H14V14H10V10Z" fill="#1e293b"/><path d="M11 11H13V13H11V11Z" fill="#ffffff"/></g>
    <g v-else-if="id === 'pixel-shroom'"><path d="M8 4H16V6H18V8H20V14H18V16H16V14H14V16H14V20H10V16H8V14H6V16H4V14H6V8H8V6H8V4Z" fill="#1e293b"/><path d="M8 6H16V8H18V14H6V8H8V6Z" fill="#ef4444"/><path d="M10 6H14V8H10V6ZM6 10H8V12H6V10ZM16 10H18V12H16V10Z" fill="#ffffff"/><path d="M10 14H14V20H10V14Z" fill="#fcd34d"/></g>
    <g v-else-if="id === 'pixel-gem'"><path d="M8 4H16L20 10V14L16 20H8L4 14V10L8 4Z" fill="#22c55e"/><path d="M9 6H15L18 10.5L15 18H9L6 10.5L9 6Z" fill="#4ade80"/><path d="M12 8L14 10.5L12 16L10 10.5L12 8Z" fill="#86efac"/></g>
    <g v-else-if="id === 'pixel-pickaxe'"><path d="M16 4H20V8H18V6H16V4ZM4 16H6V18H8V20H4V16ZM18 8H16V10H14V12H12V14H10V16H8V18H6V16H8V14H10V12H12V10H14V8H16V6H18V8Z" fill="#3b82f6"/><path d="M10 14H12V16H10V14ZM8 16H10V18H8V16ZM6 18H8V20H6V18Z" fill="#78350f"/><path d="M16 6H18V8H16V6Z" fill="#93c5fd"/></g>
    <g v-else-if="id === 'pixel-ring'"><path d="M8 4H16V6H18V8H20V16H18V18H16V20H8V18H6V16H4V8H6V6H8V4Z" fill="#eab308"/><path d="M8 6H16V8H18V16H16V18H8V16H6V8H8V6Z" fill="#facc15"/><path d="M10 8H14V16H10V8Z" fill="#1e293b"/><path d="M10 8H12V10H10V8Z" fill="#ffffff"/></g>
    <g v-else-if="id === 'pixel-star'"><path d="M12 2L14 8H20L16 12L18 18L12 14L6 18L8 12L4 8H10L12 2Z" fill="#eab308"/><path d="M12 4L13.5 8.5L18 8.5L14.5 11.5L16 16L12 13L8 16L9.5 11.5L6 8.5L10.5 8.5L12 4Z" fill="#facc15"/><path d="M10 10H11V12H10V10ZM13 10H14V12H13V10Z" fill="#1e293b"/></g>

    <g v-else-if="id === 'pixel-helmet'"><path d="M6 4H18V6H20V18H18V20H6V18H4V6H6V4Z" fill="#475569"/><path d="M8 6H16V8H8V6Z" fill="#94a3b8"/><path d="M6 8H8V16H6V8ZM16 8H18V16H16V8Z" fill="#cbd5e1"/><path d="M8 10H16V12H8V10ZM8 14H16V16H8V14Z" fill="#1e293b"/><path d="M10 2H14V4H10V2Z" fill="#dc2626"/><path d="M12 2H14V4H12V2Z" fill="#ef4444"/></g>
    <g v-else-if="id === 'pixel-axe'"><path d="M10 18H14V22H10V18Z" fill="#78350f"/><path d="M11 14H13V18H11V14Z" fill="#92400e"/><path d="M11 6H13V14H11V6Z" fill="#b45309"/><path d="M4 6H8V8H10V10H14V8H16V6H20V12H16V14H14V12H10V14H8V12H4V6Z" fill="#334155"/><path d="M4 6H6V12H4V6ZM18 6H20V12H18V6Z" fill="#94a3b8"/><path d="M6 8H8V10H6V8ZM16 8H18V10H16V8Z" fill="#cbd5e1"/></g>
    <g v-else-if="id === 'pixel-castle'"><path d="M4 8H8V10H4V8ZM16 8H20V10H16V8Z" fill="#334155"/><path d="M4 10H20V20H4V10Z" fill="#64748b"/><path d="M6 4H8V8H6V4ZM16 4H18V8H16V4Z" fill="#475569"/><path d="M8 4H16V6H8V4Z" fill="#475569"/><path d="M10 14H14V20H10V14Z" fill="#1e293b"/><path d="M10 12H14V14H10V12Z" fill="#334155"/><path d="M6 2H8V4H6V2ZM16 2H18V4H16V2Z" fill="#1d4ed8"/></g>
    <g v-else-if="id === 'pixel-shield'"><path d="M4 4H20V12C20 16.418 16.418 20 12 20C7.582 20 4 16.418 4 12V4Z" fill="#78350f"/><path d="M6 6H18V12C18 15.314 15.314 18 12 18C8.686 18 6 15.314 6 12V6Z" fill="#b45309"/><path d="M8 8H16V10H8V8Z" fill="#fcd34d"/><path d="M11 10H13V16H11V10Z" fill="#fcd34d"/></g>
    <g v-else-if="id === 'pixel-potion'"><path d="M10 2H14V6H16V8H18V18H16V20H8V18H6V8H8V6H10V2Z" fill="#e2e8f0"/><path d="M8 8H16V18H8V8Z" fill="#ef4444"/><path d="M10 10H12V12H10V10ZM14 12H16V14H14V12ZM10 14H12V16H10V14Z" fill="#fca5a5"/><path d="M11 3H13V5H11V3Z" fill="#b45309"/></g>
    <g v-else-if="id === 'pixel-scroll'"><path d="M4 4H20V20H4V4Z" fill="#fef3c7"/><path d="M4 4H6V20H4V4ZM18 4H20V20H18V4Z" fill="#dde5b6"/><path d="M6 6H18V8H6V6ZM6 16H18V18H6V16Z" fill="#d97706"/><path d="M6 10H18V12H6V10ZM6 12H18V14H6V12Z" fill="#b45309"/><path d="M2 2H22V6H2V2ZM2 18H22V22H2V18Z" fill="#78350f"/><path d="M4 2H20V4H4V2ZM4 20H20V22H4V20Z" fill="#92400e"/></g>

    <g v-else-if="id === 'pixel-cat'"><path d="M6 4H8V6H6V4ZM16 4H18V6H16V4Z" fill="#f97316"/><path d="M4 6H6V10H4V6ZM18 6H20V10H18V6Z" fill="#fb923c"/><path d="M4 10H20V18H18V20H6V18H4V10Z" fill="#fdba74"/><path d="M8 12H10V14H8V12ZM14 12H16V14H14V12Z" fill="#1e293b"/><path d="M10 14H14V16H10V14Z" fill="#fca5a5"/><path d="M6 14H8V16H6V14ZM16 14H18V16H16V14Z" fill="#fb923c"/></g>
    <g v-else-if="id === 'pixel-fox'"><path d="M4 4H8V8H4V4ZM16 4H20V8H16V4Z" fill="#ea580c"/><path d="M2 8H6V12H2V8ZM18 8H22V12H18V8Z" fill="#f97316"/><path d="M6 8H18V12H16V14H8V12H6V8Z" fill="#fb923c"/><path d="M6 12H18V18H14V20H10V18H6V12Z" fill="#ffffff"/><path d="M8 14H10V16H8V14ZM14 14H16V16H14V14Z" fill="#1e293b"/><path d="M10 18H14V20H10V18Z" fill="#1e293b"/></g>
    <g v-else-if="id === 'pixel-panda'"><path d="M4 4H8V8H4V4ZM16 4H20V8H16V4Z" fill="#1e293b"/><path d="M4 8H20V18H18V20H6V18H4V8Z" fill="#ffffff"/><path d="M6 10H10V14H6V10ZM14 10H18V14H14V10Z" fill="#1e293b"/><path d="M8 12H10V14H8V12ZM14 12H16V14H14V12Z" fill="#ffffff"/><path d="M10 16H14V18H10V16Z" fill="#1e293b"/></g>
    <g v-else-if="id === 'pixel-lion'"><path d="M4 4H20V20H4V4Z" fill="#d97706"/><path d="M6 6H18V18H6V6Z" fill="#fcd34d"/><path d="M2 8H4V16H2V8ZM20 8H22V16H20V8Z" fill="#b45309"/><path d="M8 8H10V10H8V8ZM14 8H16V10H14V8Z" fill="#78350f"/><path d="M10 12H14V14H10V12Z" fill="#1e293b"/><path d="M8 14H16V16H8V14Z" fill="#d97706"/></g>
    <g v-else-if="id === 'pixel-wolf'"><path d="M4 4H8V8H10V6H14V8H16V4H20V12H18V16H14V18H10V16H6V12H4V4Z" fill="#475569"/><path d="M8 8H10V12H8V8ZM14 8H16V12H14V8Z" fill="#cbd5e1"/><path d="M8 12H16V16H8V12Z" fill="#94a3b8"/><path d="M10 14H14V16H10V14Z" fill="#1e293b"/><path d="M8 10H10V12H8V10ZM14 10H16V12H14V10Z" fill="#ef4444"/></g>
    <g v-else-if="id === 'pixel-dragon'"><path d="M6 4H10V6H12V4H16V8H18V12H20V16H16V14H12V16H8V14H4V8H6V4Z" fill="#047857"/><path d="M8 8H10V10H8V8ZM14 8H16V10H14V8Z" fill="#fbbf24"/><path d="M8 12H16V14H8V12Z" fill="#065f46"/><path d="M10 14H14V16H10V14Z" fill="#dc2626"/><path d="M12 16H14V18H10V18H12V16Z" fill="#f97316"/><path d="M4 6H6V8H4V6ZM16 6H18V8H16V6Z" fill="#10b981"/></g>

    <g v-else-if="id === 'pixel-rocket'"><path d="M10 2H14V4H16V8H18V14H20V20H18V18H16V20H14V18H10V18H8V20H6V18H4V20H2V14H4V8H6V4H8V2Z" fill="#e2e8f0"/><path d="M10 6H14V10H10V6Z" fill="#3b82f6"/><path d="M2 14H6V18H4V20H2V14Z" fill="#ef4444"/><path d="M18 14H22V20H20V18H18V14Z" fill="#ef4444"/><path d="M8 20H10V22H8V20Z" fill="#f59e0b"/><path d="M14 20H16V22H14V20Z" fill="#f59e0b"/><path d="M10 20H14V22H10V20Z" fill="#ef4444"/></g>
    <g v-else-if="id === 'pixel-burger'"><path d="M6 6H18V10H6V6Z" fill="#d97706"/><path d="M7 6H9V7H7V6ZM14 7H16V8H14V7ZM10 8H12V9H10V8Z" fill="#fbbf24"/><path d="M4 10H20V12H4V10Z" fill="#22c55e"/><path d="M4 12H20V13H4V12Z" fill="#ef4444"/><path d="M4 13H20V15H4V13Z" fill="#78350f"/><path d="M4 15H20V16H4V15Z" fill="#facc15"/><path d="M6 16H18V18H6V16Z" fill="#d97706"/></g>
    <g v-else-if="id === 'pixel-headset'"><path d="M8 4H16V6H18V10H20V18H16V16H18V10H16V6H8V10H6V16H8V18H4V10H6V6H8V4Z" fill="#1e293b"/><path d="M2 10H6V18H2V10Z" fill="#22c55e"/><path d="M18 10H22V18H18V10Z" fill="#22c55e"/><path d="M4 12H6V16H4V12Z" fill="#4ade80"/><path d="M18 12H20V16H18V12Z" fill="#4ade80"/></g>
    <g v-else-if="id === 'pixel-bolt'"><path d="M12 2H16L18 8H22L12 22V14H8L6 8H12V2Z" fill="#facc15"/><path d="M14 2H16L18 8H20L16 14H14V10H12V2H14Z" fill="#fef08a"/><path d="M12 14H10L8 8H10V4H12V14Z" fill="#eab308"/></g>

  </svg>
</template>