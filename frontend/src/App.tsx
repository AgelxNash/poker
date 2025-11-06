import React, { useEffect, useMemo, useState } from 'react'
import DeckPicker from './components/DeckPicker'
import VotePad from './components/VotePad'
import SessionView from './components/SessionView'
import { createSession, getSession, vote as apiVote, finalize as apiFinalize, type Session } from './api'
import { connectWS, type WsMessage } from './ws'

const DECKS: Record<string,string[]> = {
  fibonacci: ['0','1','2','3','5','8','13','21','34','?'],
  powers2: ['0','1','2','4','8','16','32','?']
}

export default function App() {
  const [room, setRoom] = useState('A1')
  const [deckName, setDeckName] = useState<keyof typeof DECKS>('fibonacci')
  const [session, setSession] = useState<Session | null>(null)
  const [user, setUser] = useState('eugene')
  const [wsCtrl, setWsCtrl] = useState<ReturnType<typeof connectWS> | null>(null)
  const deck = useMemo(() => DECKS[deckName], [deckName])

  useEffect(() => {
    if (!session) return
    const ctrl = connectWS(session.id, user, (m: WsMessage) => {
      if (m.type === 'vote' || m.type === 'finalized' || m.type === 'presence') {
        // при любом событии — актуализируем сессию
        getSession(session.id!).then(setSession).catch(()=>{})
      }
    })
    setWsCtrl(ctrl)
    return () => ctrl.ws.close()
  }, [session?.id, user])

  async function handleCreate() {
    const s = await createSession(room, deckName)
    setSession(s)
  }

  async function handleVote(v: string) {
    if (!session) return
    await apiVote(session.id, user, v)
    wsCtrl?.sendVote(v)
    setSession(await getSession(session.id))
  }

  async function handleFinalize() {
    if (!session) return
    await apiFinalize(session.id)
    wsCtrl?.sendFinalize()
    setSession(await getSession(session.id))
  }

  return (
    <div style={{maxWidth:900, margin:'0 auto', padding:'24px'}}>
      <h1 style={{fontSize:28, margin:'8px 0'}}>Poker Planning</h1>
      {!session ? (
        <section style={panel()}>
          <label>Room:&nbsp;<input value={room} onChange={e=>setRoom(e.target.value)} /></label>
          <div style={{marginTop:12}}>
            <span style={{opacity:.8}}>Deck:&nbsp;</span>
            <DeckPicker value={deckName} onChange={(v)=>setDeckName(v as any)} />
          </div>
          <div style={{marginTop:12}}>
            <label>User:&nbsp;<input value={user} onChange={e=>setUser(e.target.value)} /></label>
          </div>
          <button onClick={handleCreate} style={btn()}>Create session</button>
        </section>
      ) : (
        <section style={panel()}>
          <div style={{display:'flex', gap:12, alignItems:'center'}}>
            <span>Session:</span>
            <code style={{background:'#0b1220', padding:'4px 6px', borderRadius:8}}>{session.id}</code>
            <span style={{marginLeft:'auto'}}>
              <label>User:&nbsp;<input value={user} onChange={e=>setUser(e.target.value)} /></label>
            </span>
          </div>
          <SessionView s={session} />
          {session.status === 'open' && (
            <>
              <VotePad deck={deck} onPick={handleVote} />
              <div style={{marginTop:12}}>
                <button onClick={handleFinalize} style={btn()}>Finalize</button>
              </div>
            </>
          )}
        </section>
      )}
    </div>
  )
}

function panel() {
  return { padding:'16px', border:'1px solid #1f2937', borderRadius:16, background:'#0f172a' } as React.CSSProperties
}
function btn() {
  return { marginTop:12, padding:'10px 14px', borderRadius:12, border:'1px solid #334155', background:'#111827', color:'#e6edf3', cursor:'pointer'} as React.CSSProperties
}
