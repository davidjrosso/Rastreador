import Map from '../node_modules/ol/Map.js';
import OSM, {ATTRIBUTION} from '../node_modules/ol/source/OSM.js';
import TileLayer from 'ol/layer/Tile.js';
import * as olSource from 'ol/source';
import Style from 'ol/style/Style.js';
import Icon from 'ol/style/Icon.js';
import Zoom from 'ol/control/Zoom.js';
import VectorLayer from 'ol/layer/Vector.js';
import Feature from 'ol/Feature.js';
import * as olProj from 'ol/proj';
import Point from 'ol/geom/Point.js';
import View from '../node_modules/ol/View.js';


export class MapaOl {
    #mapa;
    #zoom;
    #center;
    #target;
    constructor(
        target,
        zoom = null,
        lat = null,
        lon = null
    ){
        this.#zoom = (zoom) ? zoom : 15;
        this.#target = target;
        this.#center = [lon, lat];
        if (lon && lat) {
          this.#center = [lon, lat];
        } else {
          this.#center = [-64.11844, -32.17022];
        }
        const openCycleMapLayer = new TileLayer({
            source: new OSM({
              attributions: [
                ATTRIBUTION,
              ],
              url:
                'https://{a-c}.tile.thunderforest.com/transport/{z}/{x}/{y}.png' +
                '?apikey=d03b42dcdc084e7cbab176997685b1ce',
            }),
        });

        this.#mapa = new Map({
            layers: [openCycleMapLayer],
            controls : [],
            target: this.#target,
            view: new View({
              maxZoom: 18,
              projection: 'EPSG:4326',
              center: this.#center,
              zoom: this.#zoom,
            }),
        });
        this.#mapa.addControl(new Zoom());
        this.#mapa.on('click', function(event) {
            let point = this.getCoordinateFromPixel(event.pixel);
            let lonLat = olProj.toLonLat(point);
            let vectorLayer = this.getLayers();
            lonLat = olProj.transform(lonLat, "EPSG:4326", "EPSG:3857");
            vectorLayer.item(1).getSource().getFeatures()[0].setGeometry(new Point(lonLat))
            $("#lat").attr("value", lonLat[1]);
            $("#lon").attr("value", lonLat[0]);
          }
        );
        this.addIcon(lon, lat);
    }

    addIcon(lon, lat){
        let iconFeatures=[];
        let pos = [lon, lat];
        pos = olProj.transform(pos, "EPSG:3857", "EPSG:4326");
        let point = new Point(pos);
        point = point.transform("EPSG:4326", "EPSG:3857");
        let iconFeature = new Feature({
          geometry: point
        });
        iconFeatures.push(iconFeature);
        let vectorSource = new olSource.Vector({
          features: iconFeatures
        });
        let iconStyle = new Style({
          image: new Icon(/** @type {olx.style.IconOptions} */ ({
            anchor: [200, 500],
            anchorXUnits: 'pixels',
            anchorYUnits: 'pixels',
            scale: 0.07,
            opacity: 0.85,
            src: './images/icons/location.png'
          }))
        });
  
        let vectorLayer = new VectorLayer({
          source: vectorSource,
          style: iconStyle
        });
        this.#mapa.addLayer(vectorLayer);
    }

    addIconLayer(lon, lat) {
      let pos = [lon, lat];
      pos = olProj.transform(pos, "EPSG:3857", "EPSG:4326");
      let point = new Point(pos);
      point = point.transform("EPSG:4326", "EPSG:3857");
      let lonLat = olProj.toLonLat(point);
      let vectorLayer = this.#mapa.getLayers();
      lonLat = olProj.transform(lonLat, "EPSG:4326", "EPSG:3857");
      vectorLayer.item(1).getSource().getFeatures();
      
    }
}
