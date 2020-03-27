import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

export default class Map {

  static init () {
    let map = document.querySelector('#map')
    if (map === null) {
      return
    }
    let icon = L.icon({
      iconUrl: '/images/marker-icon.png',
    })
    let center = [map.dataset.lat, map.dataset.lng]
    map = L.map('map').setView(center, 13) //zoom de la carte : 13 définit
    let token = 'pk.eyJ1IjoicGFyaXNhcXVhIiwiYSI6ImNrN3B0Z2FuajBhN24zanFkYXBuc2N2ajYifQ.rCJcqA-8UIXyvSuA2OmdlQ'
    L.tileLayer(`https://api.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}.png?access_token=${token}`, {
      maxZoom: 19,
      minZoom: 10,
      attribution: '© <a href="https://www.mapbox.com/feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map)
    L.marker(center, {icon: icon}).addTo(map)
  }

}
