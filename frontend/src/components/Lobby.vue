
<script setup lang="ts">
import { ref } from 'vue'

const rooms = ref<{id:string, name:string}[]>([])
const loading = ref(false)
const error = ref<string | null>(null)

async function load() {
  loading.value = true
  error.value = null
  try {
    const res = await fetch('/api/rooms')
    if (!res.ok) throw new Error('HTTP ' + res.status)
    const json = await res.json()
    rooms.value = json.data ?? []
  } catch (e:any) {
    error.value = e?.message ?? String(e)
  } finally {
    loading.value = false
  }
}

async function createRoom() {
  const name = prompt('Room name?')
  if (!name) return
  const res = await fetch('/api/rooms', { method: 'POST', body: JSON.stringify({ name }) })
  if (res.ok) {
    await load()
  } else {
    alert('Failed to create')
  }
}
</script>

<template>
  <section>
    <button @click="load">Load rooms</button>
    <button @click="createRoom">Create room</button>
    <p v-if="loading">Loading...</p>
    <p v-if="error" style="color:crimson">{{ error }}</p>
    <ul>
      <li v-for="r in rooms" :key="r.id">{{ r.name }} ({{ r.id }})</li>
    </ul>
  </section>
</template>
