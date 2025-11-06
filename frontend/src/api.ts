const API = import.meta.env.VITE_API_URL || 'http://localhost:8080'

export type Session = {
  id: string
  room: string
  deck: string
  votes: Record<string,string>
  status: 'open'|'finalized'
  createdAt: number
  consensus?: string|null
}

export async function createSession(room: string, deck: string) {
  const r = await fetch(API + '/api/sessions', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ room, deck })
  })
  if (!r.ok) throw new Error('createSession failed')
  return r.json() as Promise<Session>
}

export async function getSession(id: string) {
  const r = await fetch(API + '/api/sessions/' + id)
  if (!r.ok) throw new Error('getSession failed')
  return r.json() as Promise<Session>
}

export async function vote(sessionId: string, user: string, value: string) {
  const r = await fetch(API + `/api/sessions/${sessionId}/vote`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ user, value })
  })
  if (!r.ok) throw new Error('vote failed')
  return r.json() as Promise<Session>
}

export async function finalize(sessionId: string) {
  const r = await fetch(API + `/api/sessions/${sessionId}/finalize`, { method: 'POST' })
  if (!r.ok) throw new Error('finalize failed')
  return r.json() as Promise<Session>
}
