Nova.booting((Vue, router, store) => {
    Vue.component('index-leaflet-map', require('./components/IndexField'))
    Vue.component('detail-leaflet-map', require('./components/DetailField'))
    Vue.component('form-leaflet-map', require('./components/FormField'))
})
