<template>
    <div class="flex border-b border-40">
        <div class="w-1/4 py-4">
            <slot>
                <h4 class="font-normal text-80">
                    {{ field.name }}
                </h4>
            </slot>
        </div>
        <div class="w-3/4 py-4">
            <div class="rounded flex flex-col" :style="'height: ' + field.height">
                <v-map :zoom="zoom" :center="center">

                    <!-- Layer Control -->
                    <v-control-layers position="topright"/>

                    <!-- Google Maps -->
                    <v-tilelayer-googlemutant v-for="tileProvider in googleProviders"
                    :key="tileProvider.name"
                    :name="tileProvider.name"
                    :visible="tileProvider.visible"
                    :apikey="field.googleApiKey" 
                    :options="googleMapOptions"
                    />

                    <!-- Tile Layer Groups -->
                    <v-tile-layer
                    v-for="tileProvider in tileProviders"
                    :key="tileProvider.name"
                    :name="tileProvider.name"
                    :visible="tileProvider.visible"
                    :url="tileProvider.url"
                    :attribution="tileProvider.attribution"
                    :token="token"
                    layer-type="base"/>

                    <!-- Marker Clustering -->
                    <v-marker-cluster :options="clusterOptions" @clusterclick="click()">
                    <v-geo-json v-for="geoJson in geoJsons" :key="geoJson.id" :geojson="geoJson" :options="options"/>
                    </v-marker-cluster>

                </v-map>
            </div>
        </div>
    </div>
</template>

<script>

import 'leaflet/dist/leaflet.css';
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";

import { LMap, LTileLayer, LMarker, LPopup, LControlLayers, LGridLayer,LGeoJson,LIconDefault } from 'vue2-leaflet';
import { latLng, Icon, icon } from 'leaflet';
import Vue2LeafletMarkercluster from 'vue2-leaflet-markercluster'
import Vue2LeafletGoogleMutant from 'vue2-leaflet-googlemutant'

let base64img = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAApCAYAAADAk4LOAAAFgUlEQVR4Aa1XA5BjWRTN2oW17d3YaZtr2962HUzbDNpjszW24mRt28p47v7zq/bXZtrp/lWnXr337j3nPCe85NcypgSFdugCpW5YoDAMRaIMqRi6aKq5E3YqDQO3qAwjVWrD8Ncq/RBpykd8oZUb/kaJutow8r1aP9II0WmLKLIsJyv1w/kqw9Ch2MYdB++12Onxee/QMwvf4/Dk/Lfp/i4nxTXtOoQ4pW5Aj7wpici1A9erdAN2OH64x8OSP9j3Ft3b7aWkTg/Fm91siTra0f9on5sQr9INejH6CUUUpavjFNq1B+Oadhxmnfa8RfEmN8VNAsQhPqF55xHkMzz3jSmChWU6f7/XZKNH+9+hBLOHYozuKQPxyMPUKkrX/K0uWnfFaJGS1QPRtZsOPtr3NsW0uyh6NNCOkU3Yz+bXbT3I8G3xE5EXLXtCXbbqwCO9zPQYPRTZ5vIDXD7U+w7rFDEoUUf7ibHIR4y6bLVPXrz8JVZEql13trxwue/uDivd3fkWRbS6/IA2bID4uk0UpF1N8qLlbBlXs4Ee7HLTfV1j54APvODnSfOWBqtKVvjgLKzF5YdEk5ewRkGlK0i33Eofffc7HT56jD7/6U+qH3Cx7SBLNntH5YIPvODnyfIXZYRVDPqgHtLs5ABHD3YzLuespb7t79FY34DjMwrVrcTuwlT55YMPvOBnRrJ4VXTdNnYug5ucHLBjEpt30701A3Ts+HEa73u6dT3FNWwflY86eMHPk+Yu+i6pzUpRrW7SNDg5JHR4KapmM5Wv2E8Tfcb1HoqqHMHU+uWDD7zg54mz5/2BSnizi9T1Dg4QQXLToGNCkb6tb1NU+QAlGr1++eADrzhn/u8Q2YZhQVlZ5+CAOtqfbhmaUCS1ezNFVm2imDbPmPng5wmz+gwh+oHDce0eUtQ6OGDIyR0uUhUsoO3vfDmmgOezH0mZN59x7MBi++WDL1g/eEiU3avlidO671bkLfwbw5XV2P8Pzo0ydy4t2/0eu33xYSOMOD8hTf4CrBtGMSoXfPLchX+J0ruSePw3LZeK0juPJbYzrhkH0io7B3k164hiGvawhOKMLkrQLyVpZg8rHFW7E2uHOL888IBPlNZ1FPzstSJM694fWr6RwpvcJK60+0HCILTBzZLFNdtAzJaohze60T8qBzyh5ZuOg5e7uwQppofEmf2++DYvmySqGBuKaicF1blQjhuHdvCIMvp8whTTfZzI7RldpwtSzL+F1+wkdZ2TBOW2gIF88PBTzD/gpeREAMEbxnJcaJHNHrpzji0gQCS6hdkEeYt9DF/2qPcEC8RM28Hwmr3sdNyht00byAut2k3gufWNtgtOEOFGUwcXWNDbdNbpgBGxEvKkOQsxivJx33iow0Vw5S6SVTrpVq11ysA2Rp7gTfPfktc6zhtXBBC+adRLshf6sG2RfHPZ5EAc4sVZ83yCN00Fk/4kggu40ZTvIEm5g24qtU4KjBrx/BTTH8ifVASAG7gKrnWxJDcU7x8X6Ecczhm3o6YicvsLXWfh3Ch1W0k8x0nXF+0fFxgt4phz8QvypiwCCFKMqXCnqXExjq10beH+UUA7+nG6mdG/Pu0f3LgFcGrl2s0kNNjpmoJ9o4B29CMO8dMT4Q5ox8uitF6fqsrJOr8qnwNbRzv6hSnG5wP+64C7h9lp30hKNtKdWjtdkbuPA19nJ7Tz3zR/ibgARbhb4AlhavcBebmTHcFl2fvYEnW0ox9xMxKBS8btJ+KiEbq9zA4RthQXDhPa0T9TEe69gWupwc6uBUphquXgf+/FrIjweHQS4/pduMe5ERUMHUd9xv8ZR98CxkS4F2n3EUrUZ10EYNw7BWm9x1GiPssi3GgiGRDKWRYZfXlON+dfNbM+GgIwYdwAAAAASUVORK5CYII="

delete Icon.Default.prototype._getIconUrl;

Icon.Default.imagePath = '.';
Icon.Default.mergeOptions({
  iconRetinaUrl: base64img,
  //iconUrl: '@/src/images/marker-icon.png',
  iconUrl: base64img,
  //shadowUrl: '/images/marker-shadow.png',
  //layersUrl: '@/assets/layers.png',
});


export default {
  props: ['resource', 'resourceName', 'resourceId', 'field'],
  name: 'example',
  components: {
      'v-map': LMap,
      'v-tile-layer': LTileLayer,
      'v-icondefault': LIconDefault,
      'v-marker': LMarker,
      'v-popup': LPopup,
      'v-marker-cluster': Vue2LeafletMarkercluster,
      'v-control-layers': LControlLayers,
      'v-geo-json': LGeoJson,
      'v-tilelayer-googlemutant': Vue2LeafletGoogleMutant
    },
    methods: {
      click: function (e) {
        //alert("clusterclick")
      }
    },
    data () {
      var iconMarker
      var popupName
      let geoJsons = []
      let featureType = this.field.type
      let googleMapOptions = {
        type: this.field.googleMapType
      }
      
      var visibleYesNoGoogle
      var visibleYesNoLeaflet
      var iniLocation = ''

      if (this.field.googleApiKey && this.field.googleMapType) {
          visibleYesNoGoogle = true
          visibleYesNoLeaflet = false
      } else {
          visibleYesNoGoogle = false
          visibleYesNoLeaflet = true
      }

      if (this.field.type == "GeoJson") {
          iniLocation = latLng(this.field.centerLat,this.field.centerLon)
      } else if (this.field.type == "LatLon") {
          iniLocation = latLng(this.field.latitude, this.field.longitude)
      }

      if (this.field.mapIconUrl == null) {

        iconMarker = L.icon({
            iconUrl: base64img,
            iconSize: [25, 41],
            iconAnchor: [13, 12],
            })
        } else {

        iconMarker = L.icon({
            iconUrl: this.field.mapIconUrl,
            iconSize: [100, 100],
            iconAnchor: [50, 10],
            })

        }
            
     if (this.field.type == "GeoJson") {

        geoJsons.push(JSON.parse(this.field.geoJson))
            
        } else if (this.field.type == "LatLon") {

            var geojsonCustom = {
                "type":"FeatureCollection",
                "features":[{
                    "type":"Feature",
                    "geometry":{
                        "type":"Point",
                        "coordinates":[this.field.longitude,this.field.latitude]
                    },
                    "properties":{
                        "popupContent": this.field.popupName,
                    }
              
                }]
            };

            //console.log(this.field.type)
            geoJsons.push(geojsonCustom);
        }
        
        popupName = this.field.popupName
        //console.log(this.field.popupName)
     
      
      return {
        options : {
        onEachFeature: function (feature, layer) {
            //console.log("type: " + featureType)
            if (featureType == 'LatLon') {
                layer.bindPopup(feature.properties.popupContent)
            //console.log(feature.properties.popupContent)
            } else if (featureType == 'GeoJson') {
                layer.bindPopup(feature.properties[popupName])
            }
            //console.log(popupName)
          }
        },
        googleMapOptions,
        geoJsons,
        icon:iconMarker,
        clusterOptions: {},
        //initialLocation: latLng(-34.9205, -57.953646),
        zoom:this.field.zoom,
        center: iniLocation,
        tileProviders: [
            {
                name: 'OpenStreetMap',
                visible: visibleYesNoLeaflet,
                attribution: '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
                url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
            },
        ],
        googleProviders: [
            {
                name: 'Google',
                visible: visibleYesNoGoogle
            }
        ],

      }
    },
    mounted() {
      setTimeout(() => {
        //console.log('done')
        this.$nextTick(() =>{
          this.clusterOptions = { disableClusteringAtZoom: 11 }
        });
      }, 5000);
      console.log('Removing Google...')
      if (this.field.googleApiKey == null) {
        this.googleProviders = []
      }
    }
  }
</script>
<style>
.leaflet-control-layers-toggle {
    background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAQAAAADQ4RFAAACf0lEQVR4AY1UM3gkARTePdvdoTxXKc+qTl3aU5U6b2Kbkz3Gtq3Zw6ziLGNPzrYx7946Tr6/ee/XeCQ4D3ykPtL5tHno4n0d/h3+xfuWHGLX81cn7r0iTNzjr7LrlxCqPtkbTQEHeqOrTy4Yyt3VCi/IOB0v7rVC7q45Q3Gr5K6jt+3Gl5nCoDD4MtO+j96Wu8atmhGqcNGHObuf8OM/x3AMx38+4Z2sPqzCxRFK2aF2e5Jol56XTLyggAMTL56XOMoS1W4pOyjUcGGQdZxU6qRh7B9Zp+PfpOFlqt0zyDZckPi1ttmIp03jX8gyJ8a/PG2yutpS/Vol7peZIbZcKBAEEheEIAgFbDkz5H6Zrkm2hVWGiXKiF4Ycw0RWKdtC16Q7qe3X4iOMxruonzegJzWaXFrU9utOSsLUmrc0YjeWYjCW4PDMADElpJSSQ0vQvA1Tm6/JlKnqFs1EGyZiFCqnRZTEJJJiKRYzVYzJck2Rm6P4iH+cmSY0YzimYa8l0EtTODFWhcMIMVqdsI2uiTvKmTisIDHJ3od5GILVhBCarCfVRmo4uTjkhrhzkiBV7SsaqS+TzrzM1qpGGUFt28pIySQHR6h7F6KSwGWm97ay+Z+ZqMcEjEWebE7wxCSQwpkhJqoZA5ivCdZDjJepuJ9IQjGGUmuXJdBFUygxVqVsxFsLMbDe8ZbDYVCGKxs+W080max1hFCarCfV+C1KATwcnvE9gRRuMP2prdbWGowm1KB1y+zwMMENkM755cJ2yPDtqhTI6ED1M/82yIDtC/4j4BijjeObflpO9I9MwXTCsSX8jWAFeHr05WoLTJ5G8IQVS/7vwR6ohirYM7f6HzYpogfS3R2OAAAAAElFTkSuQmCC') !important;
}
.leaflet-popup-content-wrapper {
    border-radius: 1px;
}
.leaflet-top.leaflet-left .leaflet-control-zoom {
    box-shadow: 0 0 7px #999!important;
}
.vue2leaflet-map {
    z-index: 0;
}
</style>