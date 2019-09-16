![](https://img.shields.io/github/stars/iMuMz/NovaLeafletMap?&style=flat-square)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/iMuMz/NovaLeafletMap?color=yellow&style=flat-square)
![Packagist](https://img.shields.io/packagist/dt/otrsw/leaflet-map?color=green&logo=testing&style=flat-square)
# NovaLeafletMap
Custom [Laravel Nova](https://nova.laravel.com/) map field using [Vue2Leaflet](https://korigan.github.io/Vue2Leaflet). Supports marker clustering, height, zoom, latitude and longitude coordinates, GeoJSON, marker popup and custom marker icon.

![image](https://user-images.githubusercontent.com/22936672/62364289-5cfacf80-b521-11e9-8c98-584c3978573d.png)

### Installation

```
composer require otrsw/leaflet-map
```
### Usage

```
use Otrsw\LeafletMap\LeafletMap
...
LeafletMap::make('Map View')
```
### Available Methods
You must set the height for the map to show.
#### Zoom
```
LeafletMap::make('Map View')
->zoom(8)
```
#### Height
```
LeafletMap::make('Map View')
->height('400px')
```
#### LatLon
The map will automatically center to the Lat/Lon provided.
```
LeafletMap::make('Map View')
->type('LatLon')
->point($this->my_latitude,$this->my_longitude)
```
#### GeoJSON
You have to manually set the GeoJson center.
```
LeafletMap::make('Map View')
->type('GeoJson')
->geoJson('')
->center('-6.081689','145.391881')
```
#### GeoJson Example 
```
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "properties": {},
      "geometry": {
        "type": "Point",
        "coordinates": [
          23.8623046875,
          -30.221101852485987
        ]
      }
    }
  ]
}
```

#### Popup
```
LeafletMap::make('Map View')
->popupName('My Popup Value')
```
#### Custom Marker Icon
```
LeafletMap::make('Map View')
->mapIconUrl('/images/marker-icon.png')
```
