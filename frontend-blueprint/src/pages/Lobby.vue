
<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const rooms = ref([])
const name = ref('Sprint Poker')

const fetchRooms = async () => {
  const { data } = await axios.get('/api/rooms')
  rooms.value = data
}

const createRoom = async () => {
  await axios.post('/api/rooms', { name: name.value, is_anonymous: true })
  await fetchRooms()
}

onMounted(fetchRooms)
</script>

<template>
  <div class="space-y-4">
    <div class="flex gap-2">
      <input v-model="name" class="border p-2 rounded w-64" placeholder="Room name" />
      <button @click="createRoom" class="px-4 py-2 rounded bg-black text-white">Create Room</button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
      <div v-for="r in rooms" :key="r.id" class="border rounded p-3 bg-white">
        <div class="font-semibold">{{ r.name }}</div>
        <div class="text-sm text-gray-500">anonymous: {{ r.is_anonymous }}</div>
        <router-link :to="`/room/${r.id}`" class="text-indigo-600 underline">Open</router-link>
      </div>
    </div>
  </div>
</template>
