![](https://img.shields.io/github/stars/iMuMz/NovaLeafletMap)
[![Github All Releases](https://img.shields.io/github/downloads/iMuMz/NovaLeafletMap/total.svg)]()
# NovaLeafletMap
Custom [Laravel Nova](https://nova.laravel.com/) map field using [Leaflet](https://leafletjs.com/). Supports marker clustering, height, zoom, latitude and longitude coordinates, GeoJSON, marker popup and custom marker icon.

### Installation

```
composer require otrsw/leaflet-map
```
### Usage

```
use Otrsw\LeafletMap\LeafletMap
...
LeafletMap::make('Map View','map')
```
### Available Methods
You must set the height for the map to show.
#### Zoom
```
LeafletMap::make('Map View','map')
->zoom(8)
```
#### Height
```
LeafletMap::make('Map View','map')
->height('400px')
```
#### LatLon
```
LeafletMap::make('Map View','map')
->type('LatLon')
->point($this->my_latitude,$this->my_longitude)
```
#### GeoJSON
```
LeafletMap::make('Map View','map')
->type('GeoJson')
->geoJson('')
```
#### Popup
```
LeafletMap::make('Map View','map')
->popupName('My Popup Value')
```
