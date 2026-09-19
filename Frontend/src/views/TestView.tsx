import { useEffect, useState } from 'react'
import { getPrices } from '../services/apiRequest'
import type { Price} from '../services/apiRequest'

export default function Test() {
  const [prices, setPrices] = useState<Price[]>([])
  const [error, setError] = useState('')

  useEffect(() => {
    getPrices()
      .then(data => setPrices(data.prices))
      .catch(e => setError(e.message))
  }, [])

  return (
    <div>
      <h1>Testing API connection</h1>

      {error && <p>Error: {error}</p>}
      {prices.length === 0 && !error && <p>Loading...</p>}

      {prices.map(p => (
        <p key={p.time}>
          {p.time.slice(11, 16)} - {p.huf_per_kwh} HUF/kWh
        </p>
      ))}
    </div>
  )
}