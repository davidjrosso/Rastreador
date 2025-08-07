import Map from '../node_modules/ol/Map.js';
import OSM, {ATTRIBUTION} from '../node_modules/ol/source/OSM.js';
import TileLayer from 'ol/layer/Tile.js';
import * as olSource from 'ol/source';
import Style from 'ol/style/Style.js';
import Icon from 'ol/style/Icon.js';
import * as olRender from 'ol/render';
import * as olEasing from 'ol/easing';
import {unByKey} from 'ol/Observable';
import CircleStyle from 'ol/style/Circle.js';
import FullScreen from 'ol/control/FullScreen.js';
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
    #listaFeatures=[];
    #windowsOpened=[];
    #handler;

    constructor(
      target,
      zoom = null,
      lat = null,
      lon = null
    ) {
        this.#zoom = (zoom) ? zoom : 15;
        this.#target = target;
        this.#center = [lon, lat];
        if (lon && lat) {
          this.#center = [lon, lat];
        } else {
          this.#center = [-64.11844, -32.17022];
        }
        const openCycleMapLayer = new TileLayer({
            preload: Infinity,
            source: new OSM({
              cacheSize: Infinity,
              attributions: [
                ATTRIBUTION,
              ],
              url:
                'http://{a-c}.tile.thunderforest.com/transport/{z}/{x}/{y}.png' +
                '?apikey=d03b42dcdc084e7cbab176997685b1ce'
            }),
        });

        const customMapLayer = new TileLayer({
          preload: Infinity,
          source: new OSM({
            cacheSize: Infinity,
            attributions: [
              ATTRIBUTION,
            ],
            url: '../images/tiles/{z}/{z}_{x}_{y}.png'
          }),
      });

        this.#mapa = new Map({
            layers: [openCycleMapLayer, customMapLayer],
            controls : [],
            target: this.#target,
            view: new View({
              maxZoom: 18,
              projection: 'EPSG:4326',
              center: this.#center,
              zoom: this.#zoom,
            }),
        });
        this.#mapa.addControl(new FullScreen());
        this.#mapa.addControl(new Zoom());

    }

    addPersonMap(lon, lat, id_persona) {
        let imagen = './images/icons/location.png';
        this.addIcon(lon, lat, id_persona, imagen);
    }

    addPersonMapAddress(calleNombre, nro, calleId) {
      let addres = "../Controladores/georeferenciadomiciliopersona.php?calle=" + calleId + "&nro=" + nro;
      let barrio = null;
      let barrioResp = null;
      let request = $.ajax({
        url : addres,
        success : function (data, status, requestHttp) {
            if (requestHttp.responseJSON) {
              let lon = requestHttp.responseJSON.lon;
              let lat = requestHttp.responseJSON.lat;
              let imagen = './images/icons/location.png';
              this.addIcon(
                          lat,
                          lon,
                          null,
                          imagen
                          );
              this.#mapa.getView().setCenter([lon, lat]);
              barrioResp = requestHttp.responseJSON.barrio;
              barrio = (barrioResp) ? barrioResp : "no disponible";
              $("#calle-georeferencia").text(calleNombre);
              $("#nro-georeferencia").text(nro);
              $("#barrio-georeferencia").text(barrio);
              $("#desplegable").show();
              $("#lat").val(lat);
              $("#lon").val(lon);
            }
        }.bind(this),
        error: function (data, status, requestHttp) {
          $("#calle-georeferencia").text("no disponible");
          $("#nro-georeferencia").text("no disponible");
          $("#barrio-georeferencia").text("no disponible");
          $("#desplegable").show();
        }
      });
    }

    searchStreetNumber(response) {
      let calle = response.address.road;
      let numero = response.address.house_number;
      let barrio = response.address.neighbourhood;
      calle = (calle) ? calle.trim() : "no disponible";
      numero = (numero) ? numero.trim() : "no disponible";
      barrio = (barrio) ? barrio.trim() : "no disponible";

      $("#calle-georeferencia").text(calle);
      $("#nro-georeferencia").text(numero);
      $("#barrio-georeferencia").text(barrio);
      $("#desplegable").show();
    }

    errorSearchAddress(response) {
      $("#calle-georeferencia").text("no disponible");
      $("#nro-georeferencia").text("no disponible");
      $("#barrio-georeferencia").text("no disponible");
      $("#desplegable").show();
    }

    setGeoreferenciacion() {
        let succesSearchStreetNumber = this.searchStreetNumber;
        let errorSearchAddress = this.errorSearchAddress;
        let addPerson = this.addPersonMap.bind(this);
        this.#mapa.on('click', function(event) {
          let point = this.getCoordinateFromPixel(event.pixel);
          let lonLat = olProj.toLonLat(point);
          let vectorLayer = this.getLayers();
          let request = null;
          lonLat = olProj.transform(lonLat, "EPSG:4326", "EPSG:3857");
          request = $.ajax({
            type: "GET",
            cache: false,
            url: "https://nominatim.openstreetmap.org/reverse?lat=" + lonLat[1] + "&lon=" + lonLat[0] + "&format=jsonv2",
            async: true,
            processData: false,
            contentType: false,
            success: succesSearchStreetNumber,
            error: errorSearchAddress
          });
          if (vectorLayer.item(2)) {
            vectorLayer.item(2).getSource().getFeatures()[0].setGeometry(new Point(lonLat));
          } else {
            addPerson(lonLat[1], lonLat[0], null);
          }
          $("#lat").attr("value", lonLat[1]);
          $("#lon").attr("value", lonLat[0]);
        });
    }

    viewPersonaGeoreferenciada() {
      this.#mapa.on('click', function (evt) {
        let windowsReference = null;
        const feature = this.forEachFeatureAtPixel(evt.pixel, function (feature) {
          return feature;
        });
        if (feature) {
          const coordinates = feature.getGeometry().getCoordinates();
          windowsReference = window.open(
                                         "view_modpersonas.php?ID=" + feature.get('description'),
                                         "Ventana" + feature.get('description'), 
                                         "width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no"
                                        );
        }
      });
    }

    removIcon() {
        let vectorLayer = null;
        let icon = null;
        let list = null;
        vectorLayer = this.#mapa.getLayers();

        if (vectorLayer.item(4)) {
          list = vectorLayer.item(4).getSource().getFeatures();
          icon = list.at(-1);
          if (icon && icon.values_.descripcion == "icono") {
            //vectorLayer.item(4).getSource().removeFeature(icon);
            this.#mapa.removeLayer(vectorLayer.item(4));
          }
        } else {
          list = vectorLayer.item(2).getSource().getFeatures();
          icon = list.at(-1);
          if (icon && icon.values_.descripcion == "icono") {
            //vectorLayer.item(2).getSource().removeFeature(icon);
            this.#mapa.removeLayer(vectorLayer.item(2));
          }
        }
    }

    addIcon(lon, lat, id_persona, imagen) {
        let iconFeatures=[];
        let pos = [parseFloat(lat), parseFloat(lon)];
        let point = new Point(pos);
        let vectorLayer = null;
        let icon = null;
        let list = null;
        vectorLayer = this.#mapa.getLayers();

        if (vectorLayer.item(4)) {
          list = vectorLayer.item(4).getSource().getFeatures();
          icon = list.at(-1);
        } else if (vectorLayer.item(2)){
          list = vectorLayer.item(2).getSource().getFeatures();
          icon = list.at(-1);
        }

        if (icon && icon.values_.tipo == "icono") {
           icon.setGeometry(point);
           icon.set('description', id_persona);
        } else {
          let iconFeature = new Feature({
            geometry: point,
            description: id_persona,
            tipo: "icono"
          });

          if (vectorLayer.item(4)) {
            this.#mapa.removeLayer(vectorLayer.item(4));
          }

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
    
          vectorLayer = new VectorLayer({
            source: vectorSource,
            style: iconStyle
          });
          this.#mapa.addLayer(vectorLayer);
        }
        
        this.#mapa.getView().setCenter(pos);
    }

    addIconLayerR(
                  lon,
                  lat,
                  desplazamientoY,
                  desplazamientoX,
                  elemento,
                  simbolo,
                  color
    ) {
      let pos = [parseFloat(lon), parseFloat(lat)];
      pos = add(pos, [desplazamientoY, desplazamientoX]);
      pos = olProj.transform(pos, "EPSG:3857", "EPSG:4326");
      let point = new Point(pos);
      point = point.transform("EPSG:4326", "EPSG:3857");

      let textLabel = new Feature({
        geometry: point,
        description: elemento.id_persona,
        tipo: "motivo"
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
              font: (simbolo.length == 1) ? '19px Calibri,sans-serif' : '9px Calibri,sans-serif',
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
      this.#mapa.getLayers().getArray()[2].getSource().addFeature(textLabel);
      return textLabel;
    }

    addVectorLayer(element){
      let vectorSourceText = new olSource.Vector({
        features: this.#listaFeatures,
        tipoLayer: element
      });
      let vectorLayerText = new VectorLayer({
        source: vectorSourceText,
        tipoLayer: element
      });
      this.#mapa.addLayer(vectorLayerText);
    }
    
    deleteFeatures() {
      this.#mapa.getLayers().getArray()[2].getSource().clear();
    }

    addIconLayerAnimacion(
                  lon,
                  lat,
                  desplazamientoY,
                  desplazamientoX,
                  id_persona,
                  simbolo,
                  color
    ) {
      let pos = [parseFloat(lon), parseFloat(lat)];
      pos = add(pos, [desplazamientoY, desplazamientoX]);
      pos = olProj.transform(pos, "EPSG:3857", "EPSG:4326");
      let point = new Point(pos);
      point = point.transform("EPSG:4326", "EPSG:3857");

      let textLabel = new Feature({
        geometry: point,
        description: id_persona
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
              font: (simbolo.length == 1) ? '19px Calibri,sans-serif' : '9px Calibri,sans-serif',
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
      //this.#listaFeatures.push(textLabel);
      this.#mapa.getLayers().getArray()[2].getSource().addFeature(textLabel);
      return textLabel;
    }

    revIconLayerAnimacion(
                  lon,
                  lat,
                  desplazamientoY,
                  desplazamientoX,
                  feature
    ) {
      let pos = [parseFloat(lon), parseFloat(lat)];
      let px = this.#mapa.getPixelFromCoordinate(pos);
      let featuresList = [];
      const features = this.#mapa.forEachFeatureAtPixel(px, function (feature) {
        featuresList.push(feature);
      });
      if (featuresList) {
        featuresList.forEach(feature => {
          this.#mapa.getLayers().getArray()[2].getSource().removeFeature(feature);
        });
      }
    }

    flash(e) {
      let feature = e.feature;
      const start = Date.now();
      const flashGeom = feature.getGeometry().clone();
      const listenerKey = this.#mapa.getLayers().getArray()[2].on('postrender', animate.bind(this));

      function animate(event) {
        const frameState = event.frameState;
        const elapsed = frameState.time - start;
        if (elapsed >= 3000) {
          unByKey(listenerKey);
          return;
        }
        const vectorContext = olRender.getVectorContext(event);
        const elapsedRatio = elapsed / 3000;
        const radius = olEasing.easeOut(elapsedRatio) * 25 + 5;
        const opacity = olEasing.easeOut(1 - elapsedRatio);
    
        const style = new Style({
          image: new CircleStyle({
            radius: radius,
            stroke: new Stroke({
              color: 'rgba(255, 0, 0, ' + opacity + ')',
              width: 0.25 + opacity,
            }),
          }),
        });
    
        vectorContext.setStyle(style);
        vectorContext.drawGeometry(flashGeom);
        this.#mapa.render();
      }
    }

    addHandlerSource() {
      let source = this.#mapa.getLayers().getArray()[2].getSource();
      this.#handler = source.on('addfeature', this.flash.bind(this));
    }

    deleteHandlerSource() {
      let source = this.#mapa.getLayers().getArray()[2].getSource();
      source.un('addfeature', this.flash.bind(this));
      unByKey(this.#handler);
      this.#handler = null;
    }

    layerAddToMapp() {
      let vectorSourceText = new olSource.Vector({
        features: this.#listaFeatures
      });
      let vectorLayerText = new VectorLayer({
        source: vectorSourceText
      });
      this.#mapa.addLayer(vectorLayerText);
      this.#listaFeatures = [];
    }

    addIconLayer(lon, lat) {
      let pos = [lon, lat];
      pos = olProj.transform(pos, "EPSG:3857", "EPSG:4326");
      let point = new Point(pos);
      point = point.transform("EPSG:4326", "EPSG:3857");
      let lonLat = olProj.toLonLat(point);
      let vectorLayer = this.#mapa.getLayers();
      lonLat = olProj.transform(lonLat, "EPSG:4326", "EPSG:3857");
      vectorLayer.item(2).getSource().getFeatures();
    }

    addRefWindow(refWindow) {
      this.#windowsOpened.push(refWindow);
    }

    isModifyPerson() {
      let value = null;
      value = this.#windowsOpened.reduce(
                                (valor, refWindow) => valor || refWindow.isSave,
                                false
      )
      return value;
    }
}
