<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { useRoute } from 'vue-router'
import VotePad from '../components/VotePad.vue'
import RevealPanel from '../components/RevealPanel.vue'
import TimerBar from '../components/TimerBar.vue'
import { echo } from '../echo'

const revealed = ref(false)
const votes = ref([])
const stats = ref(null)
const route = useRoute()
let channel

const onVoted = (v) => {
  console.log('voted', v)
}

const onReveal = () => {
  revealed.value = true
  // will be updated by socket event
}

onMounted(() => {
  const roomId = route.params.id
  channel = echo.channel(`room.${roomId}`)
    .listen('.vote.status', (e) => {
      console.debug('vote.status', e)
    })
    .listen('.round.revealed', (e) => {
      revealed.value = true
      votes.value = e.votes || []
      stats.value = e.stats || null
    })
})
onBeforeUnmount(() => {
  if (channel) echo.leaveChannel(channel.name)
})
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
      <div v-if="stats" class="border rounded p-3 bg-white text-sm">
        <div class="font-semibold mb-2">Stats</div>
        <div>Count: {{ stats.numeric.count }}</div>
        <div>Min/Max: {{ stats.numeric.min }} / {{ stats.numeric.max }}</div>
        <div>Avg/Median: {{ stats.numeric.avg }} / {{ stats.numeric.median }}</div>
        <div>Stdev: {{ stats.numeric.stdev }}</div>
      </div>
      <div class="border rounded p-3 bg-white">Right panel: distribution / chat</div>
    </div>
  </div>
</template>
