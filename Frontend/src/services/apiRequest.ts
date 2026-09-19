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

async function request(url: string){
    const res = await fetch('/api' + url, {
        method: 'GET',
        body: null,
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

export function getThirtyPrices(): Promise<PricesResponse>{
    return request('/prices/blocks')
}