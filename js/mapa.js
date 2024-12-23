import Map from '../node_modules/ol/Map.js';
import OSM, {ATTRIBUTION} from '../node_modules/ol/source/OSM.js';
import TileLayer from 'ol/layer/Tile.js';
import * as olSource from 'ol/source';
import Style from 'ol/style/Style.js';
import Icon from 'ol/style/Icon.js';
import Zoom from 'ol/control/Zoom.js';
import ZoomSlider from 'ol/control/ZoomSlider.js';
import VectorLayer from 'ol/layer/Vector.js';
import Feature from 'ol/Feature.js';
import * as olProj from 'ol/proj';
import Point from 'ol/geom/Point.js';
import View from '../node_modules/ol/View.js';

export function init(lat, lon) {
  if (map === null) {
      let pos = null;
      let point = null;
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

      if (lon && lat) {
        pos = [lon, lat];
        pos = olProj.transform(pos, "EPSG:3857", "EPSG:4326");
      } else {
        pos = [-64.11844, -32.17022];
      }
      point = new Point(pos);
      point = point.transform("EPSG:4326", "EPSG:3857");
      let iconFeatures=[];
      let iconFeature = new Feature({
        geometry: point,
        name: 'Null Island',
        population: 4000,
        rainfall: 500
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

      map = new Map({
        layers: [openCycleMapLayer, vectorLayer],
        controls : [],
        target: 'basicMap',
        view: new View({
          maxZoom: 18,
          projection: 'EPSG:4326',
          center: [-64.11844, -32.17022],
          zoom: 15,
        }),
      });

      map.addControl(new Zoom());
      map.addControl(new ZoomSlider());

      map.on('click', function(event) {
          let point = map.getCoordinateFromPixel(event.pixel);
          let lonLat = olProj.toLonLat(point);
          lonLat = olProj.transform(lonLat, "EPSG:4326", "EPSG:3857");
          vectorLayer.getSource().getFeatures()[0].setGeometry(new Point(lonLat))
          $("#lat").attr("value", lonLat[1]);
          $("#lon").attr("value", lonLat[0]);
        }
      );
  }
}
