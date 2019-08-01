<?php

namespace Otrsw\LeafletMap;

use Laravel\Nova\Fields\Field;

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

    public function height($height = '300px'){
        return $this->withMeta([
            'height' => $height
        ]);
    }
    public function zoom($zoom = 13){
        return $this->withMeta([
            'zoom' => $zoom
        ]);
    }
    public function geoJson($geoJson){

        return $this->withMeta([
            'geoJson' => $geoJson
        ]);
    }
    public function mapIconUrl($mapIconUrl){

        return $this->withMeta([
            'mapIconUrl' => $mapIconUrl
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


}
