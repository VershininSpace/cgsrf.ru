<template>
	<div>
		<ul class="uk-grid-match" uk-grid>
			<li class="uk-width-1-2@s">
				<div class="uk-flex uk-flex-middle">
					<div class="mapblock__wrap">
						<div class="mapblock__addr" v-for="item in addr">
							<p><strong>{{ item.adress }}</strong></p>
							<p>{{ item.desc }}</p>
							<p><a class="mapblock__link" href="#" @click.prevent="showEl(item.coord)">Смотреть на карте</a></p>
						</div>
					</div>
				</div>
			</li>
			<li class="uk-width-1-2@s uk-flex-first uk-flex-last@s">
				<div>
					<div class="mapblock__map" id="mapblock"></div>
				</div>
			</li>
		</ul>
	</div>
</template>

<script>
  import 'leaflet'
  import 'leaflet.markercluster'

  export default {
    name: "maps",

    data() {
      return {
        myMap: '',
        bounds: [],
        map_coord: [55.83342007, 49.05042200]
      }
    },
    props: {
      addr: ''
    },
    methods: {
      showEl(coords) {
        let coord = coords.split(', ');
        let map = this.myMap;
        map.setView(coord, 18);
      },
      changeMarker() {
        //console.log('ChangeMarker');
        let map = this.myMap;
        let coord = this.currentEl.map.split(', ');
        map.setView(coord, 15);
      },
      fitBounds() {
        this.myMap.fitBounds(this.bounds);
      },
      addGeoToMap() {
        let app = this;
        let map = this.myMap;
        let bounds = [];

        let markers = new L.MarkerClusterGroup();
        if (map) {
          this.addr.forEach(function (el, i) {
            let coord = el.coord.split(', ');
            let marker = L.marker(coord, {
              customTitle: el.adress
            }).bindPopup(el.adress).on('click', function () {
              //app.currentEl = el;
              //app.changeMarker();
            });
            markers.addLayer(marker);
            bounds[i] = coord;
          });
          this.bounds = bounds;
          map.addLayer(markers);
          this.fitBounds();
        }
      },
      map() {
        let map = L.map('mapblock').setView(this.map_coord, 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          //attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        this.myMap = map;
        this.addGeoToMap();

      },
    },
    mounted() {
      this.map();
    }
  }
</script>