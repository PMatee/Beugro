export type Price = {
    time: string
    eur_per_kwh: number
    huf_per_kwh:number
}
export type PricesResponse = {
    unit: string
    average:number
    prices: Price[]
}

export type SaveResponse = {
    saved: number
    above_average_blocks: string[]
}

type HourPoint = {
  hour: string
  kwh: number
}

export type ConsumptionResponse = {
  date: string
  unit: string
  total_kwh: number
  hours: HourPoint[]
}

async function request(url: string, options?: RequestInit){
    const res = await fetch('/api' + url, {
        ...options,
        headers:{
            Accept: 'application/json',
            'Content-Type': 'application/json'
        }
    })

    const data = await res.json()

    if(!res.ok){
        throw new Error(data.message || 'Request failed')
    }

    return data
}

export function getPrices(): Promise<PricesResponse>{
        return request('/prices')
}


export function getSavedBlocks(): Promise<string[]>{
    return request('/time-blocks')
}

export function saveBlocks(blocks: string[]): Promise<SaveResponse>{
    return request('/time-blocks', {
        method: 'POST',
        body: JSON.stringify({blocks}),
    })
}

export function getConsumptionData(date?: string):Promise<ConsumptionResponse>{
    return request(`/consumption${date ? `?date=${date}` : ''}`)
}
