import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

export const echo = new Echo({
  broadcaster: 'pusher',
  key: 'local',
  wsHost: (import.meta.env.VITE_WS_HOST || window.location.hostname),
  wsPort: 6001,
  forceTLS: false,
  disableStats: true,
  enabledTransports: ['ws'],
})
