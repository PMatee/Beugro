
import { useState } from 'react'
import Heatmap from './views/HeatmapView'
import BlockPicker from './views/TimeBlockView'

type Page = 'heatmap' | 'picker'

export default function App() {
  const [page, setPage] = useState<Page>('heatmap')
  return (
    <div
      style={{
        maxWidth: 900,
        margin: '0 auto',      // centers it horizontally
        padding: 24,
        fontSize: 18,          // bigger text
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
      </nav>

      {page === 'heatmap' && <Heatmap />}
      {page === 'picker' && <BlockPicker />}
    </div>
  )
}


 