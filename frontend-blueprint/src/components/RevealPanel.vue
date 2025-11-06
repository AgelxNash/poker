
<script setup>
defineProps({ votes: { type: Array, default: () => [] } })
</script>

<template>
  <div class="border rounded p-3 bg-white">
    <div class="font-semibold mb-2">Distribution (stub)</div>
    <ul class="list-disc pl-5">
      <li v-for="(v,i) in votes" :key="i">{{ v }}</li>
    </ul>
  </div>
</template>
