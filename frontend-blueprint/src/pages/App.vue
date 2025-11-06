
<template>
  <div class="min-h-screen bg-gray-50 text-gray-900">
    <header class="p-4 shadow bg-white">
      <div class="container mx-auto flex justify-between">
        <h1 class="text-xl font-semibold">Planning Poker</h1>
        <nav class="space-x-4">
          <router-link to="/" class="underline">Lobby</router-link>
        </nav>
      </div>
    </header>
    <main class="container mx-auto p-4">
      <router-view />
    </main>
  </div>
</template>
