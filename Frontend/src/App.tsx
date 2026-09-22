
import { useState } from 'react'
import Heatmap from './views/HeatmapView'
import BlockPicker from './views/TimeBlockView'
import Consumption from './views/ConsumptionView'

type Page = 'heatmap' | 'picker' | 'consumption'

export default function App() {
  const [page, setPage] = useState<Page>('heatmap')
  return (
    <div
      style={{
        maxWidth: 900,
        margin: '0 auto',      
        padding: 24,
        fontSize: 18,         
        textAlign: 'center',
      }}
    >
      <nav style={{ display: 'flex', justifyContent: 'center', gap: 12, marginBottom: 24 }}>
        <button
            onClick={() => setPage('heatmap')}
            style={{ fontWeight: page === 'heatmap' ? 'bold' : 'normal' }}
          >
            Prices
          </button>
          <button
            onClick={() => setPage('picker')}
            style={{ fontWeight: page === 'picker' ? 'bold' : 'normal' }}
          >
            Choose heating periods
          </button>
          <button
            onClick={() => setPage('consumption')}
            style={{ fontWeight: page === 'consumption' ? 'bold' : 'normal' }}
          >
            See consumption 
          </button>
      </nav>

      {page === 'heatmap' && <Heatmap />}
      {page === 'picker' && <BlockPicker />}
      {page === 'consumption' && <Consumption />}
    </div>
  )
}


 