import React from 'react'
import type { Session } from '../api'

export default function SessionView({ s }:{ s: Session }) {
  return (
    <div style={{marginTop:16}}>
      <div style={{opacity:.8}}>room: <b>{s.room}</b> | deck: <b>{s.deck}</b> | status: <b>{s.status}</b></div>
      <div style={{marginTop:8}}>
        <h3 style={{margin:'12px 0'}}>Votes</h3>
        <ul>
          {Object.entries(s.votes).map(([u,v]) => (
            <li key={u}><b>{u}:</b> {s.status==='finalized'? v : '●'}</li>
          ))}
        </ul>
        {s.status==='finalized' && (
          <div style={{marginTop:8, fontSize:18}}>Consensus: <b>{s.consensus ?? '—'}</b></div>
        )}
      </div>
    </div>
  )
}
