![](https://img.shields.io/github/stars/iMuMz/NovaLeafletMap?&style=flat-square)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/iMuMz/NovaLeafletMap?color=yellow&style=flat-square)
![Packagist](https://img.shields.io/packagist/dt/otrsw/leaflet-map?color=green&logo=testing&style=flat-square)
# NovaLeafletMap
Custom [Laravel Nova](https://nova.laravel.com/) map field using [Vue2Leaflet](https://korigan.github.io/Vue2Leaflet). Supports Google Maps, marker clustering, height, zoom, latitude and longitude coordinates, GeoJSON, marker popup and custom marker icon.

![image](https://user-images.githubusercontent.com/22936672/83330778-dd997200-a291-11ea-997d-39f10e520b91.png)

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

#### Zoom
```
LeafletMap::make('Map View')
->zoom(8)
```
#### Height
```
LeafletMap::make('Map View')
->height('400px') // default is 300px
```
#### Google Maps
By default the provider is OpenStreetMaps.<br> 
You must set your Google Maps API key (https://developers.google.com/maps/documentation/javascript/get-api-key).
```
LeafletMap::make('Map View')
->googleApiKey('')
->googleMapType('roadmap'), // roadmap, satellite or hybrid
```
#### Latitude / Longitude (Point)
The map will automatically center to the coordinates provided.
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
      "properties": {
        "popup": "I am a Popup"
      },
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
->popupName('popup')
```
#### Custom Marker Icon
```
LeafletMap::make('Map View')
->mapIconUrl('/images/marker-icon.png')
```
You can now pass additional parameters to set the icon size and anchor
```
->mapIconUrl('/images/marker-icon.png',[100,100],[50,50])
```
![Screenshot 2020-10-06 at 10 09 14](https://user-images.githubusercontent.com/22936672/95176131-b6612d00-07bc-11eb-8b1e-3489bb242a37.png)

