// src/Consumption.tsx
import { useEffect, useState } from 'react'
import { getConsumptionData } from '../services/apiRequest'
import type {ConsumptionResponse} from '../services/apiRequest'
import {
  LineChart,
  Line,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
} from 'recharts'




export default function Consumption() {
  const [data, setData] = useState<ConsumptionResponse | null>(null)
  const [message, setMessage] = useState('')

  useEffect(() => {
    getConsumptionData()
      .then(setData)
      .catch(e => setMessage('Error: ' + e.message))
  }, [])

  if (message) return <p>{message}</p>
  if (!data) return <p>Loading...</p>

  return (
    <div>
      <h2>Consumption today ({data.date})</h2>
      <p>Total: {data.total_kwh} kWh</p>

      <ResponsiveContainer width="100%" height={350}>
        <LineChart data={data.hours}>
          <CartesianGrid strokeDasharray="3 3" />
          <XAxis dataKey="hour" />
          <YAxis unit=" kWh" />
          <Tooltip />
          <Line type="monotone" dataKey="kwh" stroke="#2563eb" strokeWidth={2} />
        </LineChart>
      </ResponsiveContainer>
    </div>
  )
}
