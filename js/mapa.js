import { MapaOl } from "./MapaOl.js";

export function init(lat, 
                     lon, 
                     map
) {
if (map === null) {
    map = new MapaOl(
                     "basicMap",
                     15,
                     lat, 
                     lon
                    );
  }
  return map;
}
