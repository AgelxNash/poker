export type WsMessage =
  | { type: 'joined'; sessionId: string; user: string }
  | { type: 'presence'; sessionId: string; users: string[] }
  | { type: 'vote'; sessionId: string; user: string; value: string }
  | { type: 'finalized'; sessionId: string }

export function connectWS(sessionId: string, user: string, onMessage: (m: WsMessage) => void) {
  const URL = (import.meta.env.VITE_WS_URL || 'ws://localhost:7071')
  const ws = new WebSocket(URL)

  ws.addEventListener('open', () => {
    ws.send(JSON.stringify({ type: 'join', sessionId, user }))
  })
  ws.addEventListener('message', ev => {
    try {
      onMessage(JSON.parse(ev.data))
    } catch {}
  })

  function sendVote(value: string) {
    ws?.send(JSON.stringify({ type: 'vote', sessionId, user, value }))
  }
  function sendFinalize() {
    ws?.send(JSON.stringify({ type: 'finalize', sessionId }))
  }

  return { ws, sendVote, sendFinalize }
}
