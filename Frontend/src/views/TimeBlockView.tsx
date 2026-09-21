// src/BlockPicker.tsx
import { useEffect, useState } from 'react'
import { getPrices, getSavedBlocks, saveBlocks } from '../services/apiRequest'
import type { Price } from '../services/apiRequest'

function formatTime(iso: string) {
  return new Date(iso).toLocaleTimeString('hu-HU', {
    hour: '2-digit',
    minute: '2-digit',
    timeZone: 'Europe/Budapest',
  })
}

// 23:00 - 00:00 is not allowed
function isForbidden(iso: string) {
  return formatTime(iso).startsWith('23')
}

export default function BlockPicker() {
  const [prices, setPrices] = useState<Price[]>([])
  const [average, setAverage] = useState(0)
  const [selected, setSelected] = useState<string[]>([])
  const [message, setMessage] = useState('')
  const [saved, setSaved] = useState<string[]>([])

  useEffect(() => {
    async function load() {
      const data = await getPrices()
      const savedFromServer = await getSavedBlocks()

      

      setPrices(data.prices)
      setAverage(data.average)
      setSaved(savedFromServer)

      // show the blocks that were saved before
      const savedTimes = savedFromServer.map(s => new Date(s).getTime())
      const alreadySelected = data.prices
        .filter(p => savedTimes.includes(new Date(p.time).getTime()))
        .map(p => p.time)
      setSelected(alreadySelected)
    }

    load().catch(e => setMessage('Error: ' + e.message))
  }, [])

  function toggle(time: string) {
    if (isForbidden(time)) return

    if (selected.includes(time)) {
      setSelected(selected.filter(t => t !== time))
    } else {
      setSelected([...selected, time])
    }
  }

  async function save(e: React.MouseEvent<HTMLButtonElement>)
  {
    e.currentTarget.blur()
    try {
      const result = await saveBlocks(selected)
      setSaved(selected)
      setMessage('Saved ' + result.saved + ' blocks!')
    }
      
     catch (err) {
      setMessage('Error: ' + (err as Error).message)
    }
  }

  // selected blocks that cost more than the average
  const expensive = prices.filter(
    p => selected.includes(p.time) && p.huf_per_kwh > average
  )

  return (
    <div>
      <h2>Choose the heating periods</h2>
      <p>
        Average price: {average} HUF/kWh. Select at least 2 blocks (30 minutes).
        23:00-00:00 is not allowed.
      </p>

      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 110px)', gap: 4 }}>
        {prices.map(p => {
          const isSelected = selected.includes(p.time)
          const isSaved = saved.some(s => new Date(s).getTime() === new Date(p.time).getTime())
          const isExpensive = p.huf_per_kwh > average

          return (
            <button
              key={p.time}
              onClick={e => {toggle(p.time)
                            e.currentTarget.blur()
              }}
              disabled={isForbidden(p.time)}
              style={{
                padding: 6,
                background: isSelected ? (isSaved ? '#16a34a' : '#2563eb')   
                          : isExpensive ? '#fecaca' : '#bbf7d0',
                color: isSelected ? 'white' : 'black',
                border: isSaved ? '3px solid green' : '1px solid #888',
              }}
              
            >
              {formatTime(p.time)}
              <br />
              {p.huf_per_kwh}
            </button>
          )
        })}
      </div>

      <p>Selected: {selected.length} blocks</p>

      {expensive.length > 0 && (
        <p style={{ color: 'orange' }}>
          Warning: {expensive.length} selected block(s) are above the average price!
        </p>
      )}

      <button onClick={save} disabled={selected.length < 2}>
        Save
      </button>

      
        {saved.length === 0 ? (
      <p>Nothing saved yet.</p>
        ) : (
     <p>{message}</p>
)}
      
    </div>
  )
}
