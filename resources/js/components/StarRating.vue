
// resources/js/Components/StarRating.vue
<template>
  <div class="flex items-center space-x-1">
    <button
      v-for="star in 5"
      :key="star"
      type="button"
      @click="setRating(star * 2)"
      @mouseover="hoverRating = star * 2"
      @mouseleave="hoverRating = 0"
      class="focus:outline-none transition-colors"
    >
      <svg
        :class="[
          'w-5 h-5',
          (hoverRating || modelValue) >= star * 2
            ? 'text-yellow-400'
            : (hoverRating || modelValue) >= (star * 2 - 1)
            ? 'text-yellow-200'
            : 'text-gray-300'
        ]"
        fill="currentColor"
        viewBox="0 0 20 20"
      >
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
      </svg>
    </button>
    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
      {{ (modelValue || 0).toFixed(1) }}/10
    </span>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface Props {
  modelValue: number
}

interface Emits {
  (e: 'update:modelValue', value: number): void
}

defineProps<Props>()
const emit = defineEmits<Emits>()

const hoverRating = ref(0)

const setRating = (rating: number) => {
  emit('update:modelValue', rating)
}
</script>
