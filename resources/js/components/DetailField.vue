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
                    <v-icondefault></v-icondefault>
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
                    <l-control-layers position="topright"  ></l-control-layers>

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
  //import {iconUrl} from 'leaflet/dist/images/marker-icon.png'
  //import shadowUrl from 'leaflet/dist/images/marker-shadow.png'
//import { Icon } from 'leaflet'

delete Icon.Default.prototype._getIconUrl;

Icon.Default.imagePath = '.';
Icon.Default.mergeOptions({
  iconRetinaUrl: '/images/marker-icon-2x.png',
  iconUrl: '/images/marker-icon.png',
  shadowUrl: '/images/marker-shadow.png',
  layersUrl: '/images/layers.png',
});

function rand(n) {
    let max = n + 0.1
    let min = n - 0.1
    return Math.random() * (max - min) + min;
  }


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
      for (let i = 0; i < 100; i++) {
        locations.push({
          id: i,
          latlng: latLng(rand(-34.9205), rand(-57.953646)),
          text: 'Hola ' + i
        })
      }
      let customicon = icon(Object.assign({},
        Icon.Default.prototype.options,
        //{iconUrl}
      ))
      return {
        locations,
        icon: L.icon({
        iconUrl: '/images/red.png',
        //shadowUrl: 'http://leafletjs.com/examples/custom-icons/leaf-shadow.png',
        iconSize:     [102, 95],
        shadowSize:   [50, 64],
        iconAnchor:   [54, 94],
        shadowAnchor: [4, 62],
        popupAnchor:  [-3, -76]
      }),
        clusterOptions: {},
        initialLocation: latLng(-34.9205, -57.953646),
        zoom:this.field.zoom,
        center: latLng(-34.9205, -57.953646),
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