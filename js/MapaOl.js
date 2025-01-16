import Map from '../node_modules/ol/Map.js';
import OSM, {ATTRIBUTION} from '../node_modules/ol/source/OSM.js';
import TileLayer from 'ol/layer/Tile.js';
import * as olSource from 'ol/source';
import Style from 'ol/style/Style.js';
import Icon from 'ol/style/Icon.js';
import Zoom from 'ol/control/Zoom.js';
import {add} from 'ol/coordinate';
import VectorLayer from 'ol/layer/Vector.js';
import Text from 'ol/style/Text.js';
import Fill from 'ol/style/Fill.js';
import Stroke from 'ol/style/Stroke.js';
import Feature from 'ol/Feature.js';
import * as olProj from 'ol/proj';
import Point from 'ol/geom/Point.js';
import View from '../node_modules/ol/View.js';
import * as he from 'he/he.js';


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
        let imagen = './images/icons/location.png'
        this.addIcon(lon, lat, imagen);
    }

    setGeoreferenciacion(){
        this.#mapa.on('click', function(event) {
          let point = this.getCoordinateFromPixel(event.pixel);
          let lonLat = olProj.toLonLat(point);
          let vectorLayer = this.getLayers();
          lonLat = olProj.transform(lonLat, "EPSG:4326", "EPSG:3857");
          vectorLayer.item(1).getSource().getFeatures()[0].setGeometry(new Point(lonLat))
          $("#lat").attr("value", lonLat[1]);
          $("#lon").attr("value", lonLat[0]);
        });
    }

    viewPersonaGeoreferenciada(){
      this.#mapa.on('click', function (evt) {
        const feature = this.forEachFeatureAtPixel(evt.pixel, function (feature) {
          return feature;
        });
        if (feature) {
          const coordinates = feature.getGeometry().getCoordinates();
          window.open("view_modpersonas.php?ID=" + feature.get('description'), "Ventana" + feature.get('description'), "width=11500,height=500,scrollbars=no,top=150,left=250,resizable=no");
        }
      });
  }

    addIcon(lon, lat, imagen){
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
            src: imagen
          }))
        });
  
        let vectorLayer = new VectorLayer({
          source: vectorSource,
          style: iconStyle
        });
        this.#mapa.addLayer(vectorLayer);
    }

    addIconLayerR(lon,
                  lat,
                  desplazamientoY,
                  desplazamientoX,
                  elemento,
                  simbolo,
                  color
    ){
      let pos = [parseFloat(lon), parseFloat(lat)];
      pos = add(pos, [desplazamientoY, desplazamientoX]);
      pos = olProj.transform(pos, "EPSG:3857", "EPSG:4326");
      let point = new Point(pos);
      point = point.transform("EPSG:4326", "EPSG:3857");

      let iconFeaturesText=[];
      let textLabel = new Feature({
        geometry: point,
        description: elemento.id_persona
      });

      function styleFunction() {
        return [
          new Style({
            fill: new Fill({
              color: 'rgba(255,255,255,0.4)'
            }),
            stroke: new Stroke({
              color: '#3399CC',
              width: 1.25
            }),
            text: new Text({
              font: '12px Calibri,sans-serif',
              fill: new Fill({ color: color }),
              stroke: new Stroke({
                color: '#fff', width: 2
              }),
              text: (simbolo.length == 1) ? simbolo : he.decode("&#" + simbolo)
            })
          })
        ];
      }

      textLabel.setStyle(styleFunction);
      iconFeaturesText.push(textLabel);
      let vectorSourceText = new olSource.Vector({
        features: iconFeaturesText
      });
      let vectorLayerText = new VectorLayer({
        source: vectorSourceText
      });
      this.#mapa.addLayer(vectorLayerText);
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
