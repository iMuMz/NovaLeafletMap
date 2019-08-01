# NovaLeafletMap
Custom [Laravel Nova](https://nova.laravel.com/) map field using [Leaflet](https://leafletjs.com/). Supports marker clustering, height, zoom, latitude and longitude coordinates, GeoJSON, marker popup and custom marker icon.

### Installation

```
composer require otrsw/leaflet-map
```
### Usage

```
LeafletMap::make('Map View','map')
```
### Available Methods
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
->point('-6.081689','145.391881')
```
#### GeoJSON
```
LeafletMap::make('Map View','map')
->geoJson('')
```
#### Popup
```
LeafletMap::make('Map View','map')
->popupName('My Popup Value')
```
