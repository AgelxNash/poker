
<script setup>
import { ref } from 'vue'
const custom = ref('')
const cards = ['0','0.5','1','2','3','5','8','13','20','40','100','?','∞','☕']
const emit = defineEmits(['voted'])
const vote = (v)=> emit('voted', v)
</script>

<template>
  <div class="border rounded p-3 bg-white">
    <div class="flex flex-wrap gap-2">
      <button v-for="c in cards" :key="c" class="px-3 py-2 rounded border" @click="vote(c)">{{ c }}</button>
      <div class="flex items-center gap-2">
        <input v-model="custom" placeholder="Any number" class="border p-2 rounded w-28" />
        <button class="px-3 py-2 rounded border" @click="vote(custom)">Vote</button>
      </div>
    </div>
  </div>
</template>
