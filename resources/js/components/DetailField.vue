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
                    <!--<l-tile-layer :url="url"></l-tile-layer>-->
                    <!--<l-marker :lat-lng="marker" :icon="this.defaultIcon">
                        <l-popup>{{ popupname }}</l-popup>
                    </l-marker>-->
                    <!--<v-icondefault></v-icondefault>-->
                    <v-tile-layer
                    v-for="tileProvider in tileProviders"
                    :key="tileProvider.name"
                    :name="tileProvider.name"
                    :visible="tileProvider.visible"
                    :url="tileProvider.url"
                    :attribution="tileProvider.attribution"
                    :token="token"
                    layer-type="base"/>
                    <v-marker-cluster :options="clusterOptions" @clusterclick="click()">
                    <v-marker v-for="l in locations" :key="l.id" :lat-lng="l.latlng" :icon="icon">
                        <v-popup :content="l.text"></v-popup>
                    </v-marker>
                    </v-marker-cluster>
                    <!--<l-control-layers position="topright"  ></l-control-layers>-->

                </v-map>
            </div>
        </div>
    </div>
</template>

<script>

import 'leaflet/dist/leaflet.css';
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";

import {LMap, LTileLayer, LMarker, LPopup, LControlLayers, LGridLayer,LGeoJson,LIconDefault } from 'vue2-leaflet';
import { latLng, Icon, icon } from 'leaflet';
import Vue2LeafletMarkercluster from 'vue2-leaflet-markercluster'

var iconMarker

/*delete Icon.Default.prototype._getIconUrl;

Icon.Default.imagePath = '.';
Icon.Default.mergeOptions({
  iconRetinaUrl: 'leaflet/dist/images/marker-icon-2x.png',
  //iconUrl: '@/src/images/marker-icon.png',
  iconUrl: base64img,
  //shadowUrl: '/images/marker-shadow.png',
  layersUrl: '/images/layers.png',
});*/

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
       LControlLayers,
    },
    methods: {
      click: function (e) {
        //alert("clusterclick")
      }
    },
    data () {
    
      let locations = []

      if (this.field.mapIconUrl == null) {
        let base64img = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAApCAYAAADAk4LOAAAFgUlEQVR4Aa1XA5BjWRTN2oW17d3YaZtr2962HUzbDNpjszW24mRt28p47v7zq/bXZtrp/lWnXr337j3nPCe85NcypgSFdugCpW5YoDAMRaIMqRi6aKq5E3YqDQO3qAwjVWrD8Ncq/RBpykd8oZUb/kaJutow8r1aP9II0WmLKLIsJyv1w/kqw9Ch2MYdB++12Onxee/QMwvf4/Dk/Lfp/i4nxTXtOoQ4pW5Aj7wpici1A9erdAN2OH64x8OSP9j3Ft3b7aWkTg/Fm91siTra0f9on5sQr9INejH6CUUUpavjFNq1B+Oadhxmnfa8RfEmN8VNAsQhPqF55xHkMzz3jSmChWU6f7/XZKNH+9+hBLOHYozuKQPxyMPUKkrX/K0uWnfFaJGS1QPRtZsOPtr3NsW0uyh6NNCOkU3Yz+bXbT3I8G3xE5EXLXtCXbbqwCO9zPQYPRTZ5vIDXD7U+w7rFDEoUUf7ibHIR4y6bLVPXrz8JVZEql13trxwue/uDivd3fkWRbS6/IA2bID4uk0UpF1N8qLlbBlXs4Ee7HLTfV1j54APvODnSfOWBqtKVvjgLKzF5YdEk5ewRkGlK0i33Eofffc7HT56jD7/6U+qH3Cx7SBLNntH5YIPvODnyfIXZYRVDPqgHtLs5ABHD3YzLuespb7t79FY34DjMwrVrcTuwlT55YMPvOBnRrJ4VXTdNnYug5ucHLBjEpt30701A3Ts+HEa73u6dT3FNWwflY86eMHPk+Yu+i6pzUpRrW7SNDg5JHR4KapmM5Wv2E8Tfcb1HoqqHMHU+uWDD7zg54mz5/2BSnizi9T1Dg4QQXLToGNCkb6tb1NU+QAlGr1++eADrzhn/u8Q2YZhQVlZ5+CAOtqfbhmaUCS1ezNFVm2imDbPmPng5wmz+gwh+oHDce0eUtQ6OGDIyR0uUhUsoO3vfDmmgOezH0mZN59x7MBi++WDL1g/eEiU3avlidO671bkLfwbw5XV2P8Pzo0ydy4t2/0eu33xYSOMOD8hTf4CrBtGMSoXfPLchX+J0ruSePw3LZeK0juPJbYzrhkH0io7B3k164hiGvawhOKMLkrQLyVpZg8rHFW7E2uHOL888IBPlNZ1FPzstSJM694fWr6RwpvcJK60+0HCILTBzZLFNdtAzJaohze60T8qBzyh5ZuOg5e7uwQppofEmf2++DYvmySqGBuKaicF1blQjhuHdvCIMvp8whTTfZzI7RldpwtSzL+F1+wkdZ2TBOW2gIF88PBTzD/gpeREAMEbxnJcaJHNHrpzji0gQCS6hdkEeYt9DF/2qPcEC8RM28Hwmr3sdNyht00byAut2k3gufWNtgtOEOFGUwcXWNDbdNbpgBGxEvKkOQsxivJx33iow0Vw5S6SVTrpVq11ysA2Rp7gTfPfktc6zhtXBBC+adRLshf6sG2RfHPZ5EAc4sVZ83yCN00Fk/4kggu40ZTvIEm5g24qtU4KjBrx/BTTH8ifVASAG7gKrnWxJDcU7x8X6Ecczhm3o6YicvsLXWfh3Ch1W0k8x0nXF+0fFxgt4phz8QvypiwCCFKMqXCnqXExjq10beH+UUA7+nG6mdG/Pu0f3LgFcGrl2s0kNNjpmoJ9o4B29CMO8dMT4Q5ox8uitF6fqsrJOr8qnwNbRzv6hSnG5wP+64C7h9lp30hKNtKdWjtdkbuPA19nJ7Tz3zR/ibgARbhb4AlhavcBebmTHcFl2fvYEnW0ox9xMxKBS8btJ+KiEbq9zA4RthQXDhPa0T9TEe69gWupwc6uBUphquXgf+/FrIjweHQS4/pduMe5ERUMHUd9xv8ZR98CxkS4F2n3EUrUZ10EYNw7BWm9x1GiPssi3GgiGRDKWRYZfXlON+dfNbM+GgIwYdwAAAAASUVORK5CYII="
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

      var markers = [
        {
            "popupName":"Goroka",
            "city":"Goroka, Papua New Guinea",
            "iata_faa":"GKA",
            "icao":"AYGA",
            "lat":-6.081689,
            "lng":145.391881,
            "alt":5282,
            "tz":"Pacific/Port_Moresby"
        },{
            "popupName":"Madang",
            "city":"Madang, Papua New Guinea",
            "iata_faa":"MAG",
            "icao":"AYMD",
            "lat":-5.207083,
            "lng":145.7887,
            "alt":20,
            "tz":"Pacific/Port_Moresby"
        }
      ]

      /*var markers = [
        {
            "type": "FeatureCollection",
                "features": [
                    {
                    "type": "Feature",
                    "geometry": {
                        "type": "Point",
                        "coordinates": [-6.081689,145.391881]
                    },
                    "properties": {
                        "prop0": "value0"
                    }
                    },
                    
                ]}
            ]*/
    //console.log(this.field.type)
     if (this.field.type == "GeoJson") {

            for (var i in markers) {
            //console.log(markers[i].features[0].geometry.coordinates)
            
            locations.push({
                id: i,
                //latlng: latLng(markers[i].features[0].geometry.coordinates),
                latlng: latLng(markers[i].lat,markers[i].lng),
                text: 'Hola ' + markers[i].popupName
                })
            }
        } else if (this.field.type == "LatLon") {

            locations.push({
            //id: i,
            //latlng: latLng(markers[i].features[0].geometry.coordinates),
            latlng: latLng(this.field.latitude,this.field.longitude),
            text: 'Hola ' + this.field.popupName
            })

            }
     
      /*let customicon = icon(Object.assign({},
        Icon.Default.prototype.options,
        //{iconUrl}
      ))*/
      return {
        locations,
        icon:iconMarker,
        clusterOptions: {},
        //initialLocation: latLng(-34.9205, -57.953646),
        zoom:this.field.zoom,
        center: latLng(-6.081689, 145.391881),
        tileProviders: [
            {
            name: 'OpenStreetMap',
            visible: true,
            attribution: '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
            },

        ],

      }
    },
    mounted() {
      setTimeout(() => {
        console.log('done')
        this.$nextTick(() =>{
          this.clusterOptions = { disableClusteringAtZoom: 11 }
        });
      }, 5000);
    }
  }
</script>
<style>
.leaflet-control-layers-toggle {
    background-image: url('/images/layers.png');
}
</style>