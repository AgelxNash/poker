import React from 'react'

type Props = {
  deck: string[]
  onPick: (v:string)=>void
}

export default function VotePad({ deck, onPick }: Props) {
  return (
    <div style={{display:'grid', gridTemplateColumns:'repeat(auto-fit, minmax(64px, 1fr))', gap:12, marginTop:12}}>
      {deck.map(v => (
        <button key={v} onClick={() => onPick(v)} style={card()}>{v}</button>
      ))}
    </div>
  )
}

function card() {
  return {
    padding:'24px 0',
    borderRadius:16,
    border:'1px solid #243447',
    background:'#111827',
    color:'#e6edf3',
    fontSize:20,
    cursor:'pointer'
  } as React.CSSProperties
}
