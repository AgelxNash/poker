
<script setup>
import { ref, onMounted } from 'vue'
const props = defineProps({ seconds: { type: Number, default: 120 } })
const left = ref(props.seconds)
onMounted(() => {
  const t = setInterval(() => {
    left.value = Math.max(0, left.value - 1)
    if (left.value === 0) clearInterval(t)
  }, 1000)
})
</script>

<template>
  <div class="border rounded p-3 bg-white">
    <div>Time left: {{ left }}s</div>
    <div class="w-full h-2 bg-gray-200 mt-2 rounded">
      <div class="h-2 bg-gray-800 rounded" :style="{ width: (left / seconds * 100) + '%' }"></div>
    </div>
  </div>
</template>
