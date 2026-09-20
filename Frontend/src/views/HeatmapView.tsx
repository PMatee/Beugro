import {useEffect, useState} from 'react'
import { getPrices } from "../services/apiRequest";
import type { Price } from "../services/apiRequest";

function getColor(price:number, min:number, max:number){
    const ratio = (price-min)/(max-min)
    const hue = 120- ratio * 120
    return 'hsl('+hue+', 80%, 45%)'
}

export default function Heatmap(){
    const [prices, setPrices] = useState<Price[]>([])

    useEffect(()=>{
        getPrices().then(data => setPrices(data.prices))
    }, [])

    if(prices.length === 0){
        return <p>Loading</p>
    }

    const values = prices.map(p => p.huf_per_kwh)
    const min = Math.min(...values)
    const max = Math.max(...values)

    return(
        <div>
            <h1>Prices (HUF/kWh)</h1>
            <div style={{display: 'flex'}}>
                {prices.map(p=>(
                    <div 
                    key ={p.time}
                    title={p.time.slice(11,16) + ' - ' + p.huf_per_kwh}
                    style={{
                        width: 12,
                        height:40,
                        background: getColor(p.huf_per_kwh, min,max)
                    }}/>
                ))}
            </div>

            <p>Green = cheap, red = expensive</p>
        </div>
    )

}