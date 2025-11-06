
import { createRouter, createWebHistory } from 'vue-router'
import Lobby from '../pages/Lobby.vue'
import Room from '../pages/Room.vue'

const routes = [
  { path: '/', name: 'lobby', component: Lobby },
  { path: '/room/:id', name: 'room', component: Room },
]

export default createRouter({
  history: createWebHistory(),
  routes,
})
