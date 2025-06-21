<template>
  <Link
    :href="href"
    :class="cn(
      'flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-colors',
      'hover:bg-accent hover:text-accent-foreground',
      'focus:bg-accent focus:text-accent-foreground focus:outline-none',
      active
        ? 'bg-accent text-accent-foreground'
        : 'text-muted-foreground'
    )"
    @click="$emit('click')"
  >
    <slot />
  </Link>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'

interface Props {
  href: string
  active?: boolean
}

defineProps<Props>()
defineEmits<{
  click: []
}>()
</script>
