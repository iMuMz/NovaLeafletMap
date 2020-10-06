<?php

namespace Otrsw\LeafletMap;

use Laravel\Nova\Fields\Field;
use Log;

class LeafletMap extends Field
{
    public $showOnUpdate = false;
    public $showOnIndex = false;
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'leaflet-map';

    public function height($height){
        return $this->withMeta([
            'height' => $height
        ]);
    }
    public function zoom($zoom){
        return $this->withMeta([
            'zoom' => $zoom
        ]);
    }
    public function geoJson($geoJson){

        return $this->withMeta([
            'geoJson' => $geoJson
        ]);
    }
    public function mapIconUrl($mapIconUrl,$mapIconSize = null,$mapIconAnchor = null){

        return $this->withMeta([
            'mapIconUrl' => $mapIconUrl,
            'mapIconSize' => $mapIconSize,
            'mapIconAnchor' => $mapIconAnchor
        ]);
    }

    public function type($type){
        return $this->withMeta([
            'type' => $type
        ]);
    }
    public function popupName($popupName){
        return $this->withMeta([
            'popupName' => $popupName
        ]);
    }
    public function point($latitude,$longitude) {
        return $this->withMeta([
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);
    }

    public function center($centerLat,$centerLon) {
        return $this->withMeta([
            'centerLat' => $centerLat,
            'centerLon' => $centerLon
        ]);
    }

    /*public function html($html) {
        return $this->withMeta([
            'html' => $html,
        ]);
    }*/

    public function googleApiKey($googleApiKey) {

        return $this->withMeta([
            'googleApiKey' => $googleApiKey
        ]);
    }

    public function googleMapType($googleMapType) {

        return $this->withMeta([
            'googleMapType' => $googleMapType
        ]);
    }


}
