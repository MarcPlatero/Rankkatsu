<script setup>
import { ref, watch, onMounted } from 'vue'

const props = defineProps({
  value: { type: Number, required: true },
  duration: { type: Number, default: 1500 }
})

const displayValue = ref(0)

const animate = (start, end) => {
  let startTime = null
  const change = end - start
  const step = (timestamp) => {
    if (!startTime) startTime = timestamp
    const progress = Math.min((timestamp - startTime) / props.duration, 1)
    const ease = 1 - Math.pow(1 - progress, 4)
    displayValue.value = Math.round(start + (change * ease))
    if (progress < 1) window.requestAnimationFrame(step)
  }
  window.requestAnimationFrame(step)
}

onMounted(() => animate(0, props.value))
watch(() => props.value, (newVal, oldVal) => animate(oldVal || 0, newVal))
</script>

<template>
  <span>{{ displayValue }}</span>
</template>