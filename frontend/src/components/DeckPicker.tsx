import React from 'react'

const presets: Record<string, string[]> = {
  fibonacci: ['0','1','2','3','5','8','13','21','34','?'],
  powers2: ['0','1','2','4','8','16','32','?']
}

export default function DeckPicker({ value, onChange }:{ value:string, onChange:(v:string)=>void }) {
  return (
    <div style={{display:'flex', gap:8, flexWrap:'wrap'}}>
      {Object.keys(presets).map(name => (
        <button
          key={name}
          onClick={() => onChange(name)}
          style={{padding:'6px 10px', borderRadius:12, border:'1px solid #2d3748', background:value===name?'#192734':'#0b0f14', color:'#e6edf3', cursor:'pointer'}}
        >{name}</button>
      ))}
    </div>
  )
}
