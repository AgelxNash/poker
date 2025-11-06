
<script setup>
import { ref } from 'vue'
import VotePad from '../components/VotePad.vue'
import RevealPanel from '../components/RevealPanel.vue'
import TimerBar from '../components/TimerBar.vue'

const revealed = ref(false)
const votes = ref([])

const onVoted = (v) => {
  console.log('voted', v)
}

const onReveal = () => {
  revealed.value = true
  votes.value = [] // fetch from API in future
}
</script>

<template>
  <div class="grid gap-4 md:grid-cols-3">
    <div class="md:col-span-2 space-y-4">
      <VotePad @voted="onVoted" />
      <TimerBar :seconds="120" />
      <div class="flex gap-2">
        <button class="px-4 py-2 bg-black text-white rounded" @click="onReveal">Reveal</button>
        <button class="px-4 py-2 bg-gray-800 text-white rounded">Revote</button>
        <button class="px-4 py-2 bg-gray-200 text-gray-900 rounded">Finalize</button>
      </div>
    </div>
    <div class="space-y-4">
      <RevealPanel v-if="revealed" :votes="votes" />
      <div class="border rounded p-3 bg-white">Right panel: distribution / chat</div>
    </div>
  </div>
</template>
