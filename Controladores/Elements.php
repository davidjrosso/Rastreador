<?php  
require_once("Conexion.php");

header("Content-Type: text/html;charset=utf-8");

class Elements{
  const PAGINA_INICIO = 0;
  const PAGINA_PERSONA = 1;
  const PAGINA_MOVIMIENTO = 10;
  const PAGINA_USUARIO = 20;
  const PAGINA_CATEGORIA = 3;
  const PAGINA_MOTIVO = 2;
  const PAGINA_RESPONSABLE = 4;
  const PAGINA_CENTRO_SALUD = 5;
  const PAGINA_ESCUELA = 6;
  const PAGINA_OTRAS_INSTITUCIONES = 7;
  const PAGINA_BARRIO = 8;
  const PAGINA_CALLE = 9;
  const PAGINA_REPORTE_GRAFICO = 11;
  const PAGINA_REPORTE_LISTADO = 12;
  const PAGINA_UNIFICACION_PERSONA = 13;
  const PAGINA_UNIFICACION_MOTIVO = 14;
  const PAGINA_UNIFICACION_CENTRO_SALUD = 15;
  const PAGINA_UNIFICACION_ESCUELA = 16;
  const PAGINA_UNIFICACION_BARRIO = 17;
  const PAGINA_UNIFICACION_OTRA_INSTITUCION = 18;
  const PAGINA_UNIFICACION_CALLE = 19;

	//Instanciando la Conexion
	public function __construct(){

	}

	public function getMenuGeneral($ID)
  {
    switch ($ID) {
      case 1: echo '<div class="menu-list">
                      <ul id="menu-content" class="menu-content collapse out">
                          <li class="collapsed active" onClick = "location.href = \'view_movimientos.php\'">
                            <a href="view_movimientos.php"><i class="fa fa-file-text fa-lg"></i> Movimientos</a>
                          </li>
                      </ul>
                    </div>';
        break;
      default: echo '<div class="menu-list">
                      <ul id="menu-content" class="menu-content collapse out">
                          <li class="collapsed" onClick = "location.href = \'view_movimientos.php\'">
                            <a href="view_movimientos.php"><i class="fa fa-file-text fa-lg"></i> Movimientos</a>
                          </li>
                      </ul>
                     </div>';
                     break;   
    }
}

public function getMenuGeneralUsuario($ID)
{
  switch ($ID) {
    case self::PAGINA_MOVIMIENTO : 
        $menu = '<div class="menu-list">
                    <ul id="menu-content" class="menu-content collapse out">
                        <li class="collapsed active" onClick = "location.href = \'view_movimientos.php\'">
                          <a href="view_movimientos.php"><i class="fa fa-file-text fa-lg"></i> Movimientos</a>
                        </li>
                    </ul>
                  </div>';
        break;
    default: 
        $menu = '<div class="menu-list">
                    <ul id="menu-content" class="menu-content collapse out">
                        <li class="collapsed" onClick = "location.href = \'view_movimientos.php\'">
                          <a href="view_movimientos.php"><i class="fa fa-file-text fa-lg"></i> Movimientos</a>
                        </li>
                    </ul>
                   </div>';
        break;   
  }
  return $menu;
}

	public function getMenuActualizaciones($ID){
		switch ($ID) {
			case  self::PAGINA_PERSONA:
            echo '<div class="menu-list">
                    <ul id="menu-content" class="menu-content collapse out">
                        <li class="collapsed active" onClick = "location.href = \'view_personas.php\'">
                          <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                          <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                          <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                        </li>  
                        <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                          <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                          <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                          <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                          <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                          <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                        <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                        </li>
                    </ul>
                   </div>';
            break;
			case self::PAGINA_MOTIVO:
           echo '<div class="menu-list">
              <ul id="menu-content" class="menu-content collapse out">
                  <li onClick = "location.href = \'view_personas.php\'">
                    <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                  </li>
                  <li class="collapsed active" onClick = "location.href = \'view_motivos.php\'">
                    <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                  </li>
                  <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                    <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                  </li>  
                  <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                    <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                  </li>
                  <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                    <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                  </li>
                  <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                    <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                  </li>
                  <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                    <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                  </li>
                  <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                    <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                  </li>
                  <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                  <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                  </li>
              </ul>
          </div>';
          break;
			case self::PAGINA_CATEGORIA:
           echo '<div class="menu-list">
    
              <ul id="menu-content" class="menu-content collapse out">
                  <li onClick = "location.href = \'view_personas.php\'">
                    <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                  </li>
                  <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                    <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                  </li>
                  <li class="collapsed active" onClick = "location.href = \'view_categorias.php\'">
                    <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                  </li>  
                  <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                    <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                  </li>
                  <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                    <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                  </li>
                  <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                    <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                  </li>
                  <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                    <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                  </li>
                  <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                    <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                  </li>
                  <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                  <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                  </li>
              </ul>
          </div>';
          break;
     		case self::PAGINA_RESPONSABLE:
            echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed active" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
     		</div>';
        break;
     		case self::PAGINA_CENTRO_SALUD:
            echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
     		</div>';
        break;  
        	case self::PAGINA_ESCUELA:
            echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
        	</div>';
          break; 
          case self::PAGINA_OTRAS_INSTITUCIONES:
            echo '<div class="menu-list">
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
          </div>';break;        
          case self::PAGINA_BARRIO:
              echo '<div class="menu-list">
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
          </div>';break;
          case self::PAGINA_CALLE:
            echo '<div class="menu-list">
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
              </li>
            </ul>
          </div>';break;
			default:
        echo '<div class="menu-list">
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
     		</div>';break;
		}
		
	}

  public function getMenuReportes($ID){
    switch ($ID) {
      case 1:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li class="collapsed active" onClick = "location.href = \'view_general_new.php\'">
                  <a href="view_general_new.php"><i class="fa fa-file-text fa-lg"></i> Gráfico</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_listados.php\'">
                  <a href="view_listados.php"><i class="fa fa-file-text fa-lg"></i> Listados</a>
                </li>
            </ul>
        </div>';break;
      case 2:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li class="collapsed" onClick = "location.href = \'view_general_new.php\'">
                  <a href="view_general_new.php"><i class="fa fa-file-text fa-lg"></i> Gráfico</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_listados.php\'">
                  <a href="view_listados.php"><i class="fa fa-file-text fa-lg"></i> Listados</a>
                </li>
            </ul>
        </div>';break;
      default:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li class="collapsed" onClick = "location.href = \'view_general_new.php\'">
                  <a href="view_general_new.php"><i class="fa fa-file-text fa-lg"></i> Gráfico</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_listados.php\'">
                  <a href="view_listados.php"><i class="fa fa-file-text fa-lg"></i> Listados</a>
                </li>
            </ul>
        </div>';break;
    }
    
  }

  public function getMenuUnificacion($ID){
    switch ($ID) {
      case 1:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li class="collapsed active" onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
            </ul>
        </div>';break;
      case 2:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
            </ul>
        </div>';break;
        case 3:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
            </ul>
        </div>';break;
        case 4:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
            </ul>
        </div>';break;
        case 5:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
            </ul>
        </div>';break;
        case 6:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
            </ul>
        </div>';break;
        case 7:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
            </ul>
        </div>';break;
      default:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa  fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifdirecciones.php\'">
                  <a href="view_unifdirecciones.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
        </div>';break;
    }
    
  }

public function getMenuSeguridad($ID){
    switch ($ID) {
      case 1:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li class="collapsed active" onClick = "location.href = \'view_usuarios.php\'">
                  <a href="view_usuarios.php"><i class="fa fa-file-text fa-lg"></i> Usuarios</a>
                </li>
            </ul>
        </div>';break;
      case 2:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_usuarios.php\'">
                  <a href="view_usuarios.php"><i class="fa fa-file-text fa-lg"></i> Usuarios</a>
                </li>
            </ul>
        </div>';break;
      default:echo '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_usuarios.php\'">
                  <a href="view_usuarios.php"><i class="fa fa-file-text fa-lg"></i> Usuarios</a>
                </li>
            </ul>
        </div>';break;
    }
    
  }

  public function getMenuHistorial($ID){
    switch ($ID) {
      case 1:echo '<div class="menu-list">  
            <ul id="menu-content" class="menu-content collapse out">
                <li class="collapsed active" onClick = "location.href = \'para_que_sirve.pdf\'">
                  <a href="para_que_sirve.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Para qué sirve?</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'como_funciona.pdf\'">
                  <a href="como_funciona.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Cómo funciona?</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'licencia.pdf\'">
                  <a href="licencia.pdf"><i class="fa fa-file-text fa-lg"></i> Licencia</a>
                </li>
               
            </ul>
        </div>';break;
	   case 2:echo '<div class="menu-list">
	        <ul id="menu-content" class="menu-content collapse out">
	            <li class="collapsed" onClick = "location.href = \'para_que_sirve.pdf\'">
	              <a href="para_que_sirve.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Para qué sirve?</a>
	            </li>
	            <li class="collapsed active" onClick = "location.href = \'como_funciona.pdf\'">
                  <a href="como_funciona.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Cómo funciona?</a>
              </li>
              <li class="collapsed" onClick = "location.href = \'licencia.pdf\'">
                  <a href="licencia.pdf"><i class="fa fa-file-text fa-lg"></i> Licencia</a>
              </li>
             
	        </ul>
	    </div>';break;
      case 3:echo '<div class="menu-list">
	        <ul id="menu-content" class="menu-content collapse out">
	            <li class="collapsed" onClick = "location.href = \'para_que_sirve.pdf\'">
	              <a href="para_que_sirve.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Para qué sirve?</a>
	            </li>
	            <li class="collapsed" onClick = "location.href = \'como_funciona.pdf\'">
                  <a href="como_funciona.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Cómo funciona?</a>
              </li>
              <li class="collapsed active" onClick = "location.href = \'licencia.pdf\'">
                  <a href="licencia.pdf"><i class="fa fa-file-text fa-lg"></i> Licencia</a>
              </li>
             
	        </ul>
	    </div>';break;
      default:echo '<div class="menu-list">
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'para_que_sirve.pdf\'">
                  <a href="para_que_sirve.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Para qué sirve?</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'como_funciona.pdf\'">
                  <a href="como_funciona.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Cómo funciona?</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'licencia.pdf\'">
                  <a href="licencia.pdf"><i class="fa fa-file-text fa-lg"></i> Licencia</a>
                </li>                
            </ul>
        </div>';break;
    }
    
  }

  public function getMenuReportesUsuario($ID){
    switch ($ID) {
      case 11:
          $menu = '<div class="menu-list">
                    <ul id="menu-content" class="menu-content collapse out">
                        <li class="collapsed active" onClick = "location.href = \'view_general_new.php\'">
                          <a href="view_general_new.php"><i class="fa fa-file-text fa-lg"></i> Gráfico</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_listados.php\'">
                          <a href="view_listados.php"><i class="fa fa-file-text fa-lg"></i> Listados</a>
                        </li>
                    </ul>
                  </div>';
                  break;
      case 12:
          $menu = '<div class="menu-list">
                    <ul id="menu-content" class="menu-content collapse out">
                        <li class="collapsed" onClick = "location.href = \'view_general_new.php\'">
                          <a href="view_general_new.php"><i class="fa fa-file-text fa-lg"></i> Gráfico</a>
                        </li>
                        <li class="collapsed active" onClick = "location.href = \'view_listados.php\'">
                          <a href="view_listados.php"><i class="fa fa-file-text fa-lg"></i> Listados</a>
                        </li>
                    </ul>
                   </div>';
          break;
      default:
          $menu = '<div class="menu-list">
                    <ul id="menu-content" class="menu-content collapse out">
                        <li class="collapsed" onClick = "location.href = \'view_general_new.php\'">
                          <a href="view_general_new.php"><i class="fa fa-file-text fa-lg"></i> Gráfico</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_listados.php\'">
                          <a href="view_listados.php"><i class="fa fa-file-text fa-lg"></i> Listados</a>
                        </li>
                    </ul>
                    </div>';
          break;
    }
    return $menu;
  }

  public function getMenuUnificacionUsuario($ID){
    switch ($ID) {
      case 13:
          $menu = '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li class="collapsed active" onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifdirecciones.php\'">
                  <a href="view_unifdirecciones.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
        </div>';break;
      case 14:
          $menu = '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifdirecciones.php\'">
                  <a href="view_unifdirecciones.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
        </div>';break;
        case 15:
            $menu = '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifdirecciones.php\'">
                  <a href="view_unifdirecciones.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
        </div>';break;
        case 16:
            $menu = '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifdirecciones.php\'">
                  <a href="view_unifdirecciones.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
        </div>';break;
        case 17:
            $menu =  '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifdirecciones.php\'">
                  <a href="view_unifdirecciones.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
        </div>';break;
        case 18:
            $menu =  '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifdirecciones.php\'">
                  <a href="view_unifdirecciones.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
        </div>';break;
        case 19:
            $menu =  '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_unifdirecciones.php\'">
                  <a href="view_unifdirecciones.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
        </div>';
        break;
      default:
          $menu =  '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_unifpersonas.php\'">
                  <a href="view_unifpersonas.php"><i class="fa fa-file-text fa-lg"></i> Personas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifmotivos.php\'">
                  <a href="view_unifmotivos.php"><i class="fa fa-file-text fa-lg"></i> Motivos</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifcentros.php\'">
                  <a href="view_unifcentros.php"><i class="fa  fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifescuelas.php\'">
                  <a href="view_unifescuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuelas</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifbarrios.php\'">
                  <a href="view_unifbarrios.php"><i class="fa fa-file-text fa-lg"></i> Barrios</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifotrasinstituciones.php\'">
                  <a href="view_unifotrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_unifdirecciones.php\'">
                  <a href="view_unifdirecciones.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
        </div>';break;
    }
    return $menu;
  }

public function getMenuSeguridadUsuario($ID){
    switch ($ID) {
      case self::PAGINA_USUARIO: 
          $menu = '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li class="collapsed active" onClick = "location.href = \'view_usuarios.php\'">
                  <a href="view_usuarios.php"><i class="fa fa-file-text fa-lg"></i> Usuarios</a>
                </li>
            </ul>
        </div>';break;
      case 2:
          $menu =  '<div class="menu-list">            
                      <ul id="menu-content" class="menu-content collapse out">
                          <li onClick = "location.href = \'view_usuarios.php\'">
                            <a href="view_usuarios.php"><i class="fa fa-file-text fa-lg"></i> Usuarios</a>
                          </li>
                      </ul>
                  </div>';
          break;
      default:
          $menu =  '<div class="menu-list">
                      <ul id="menu-content" class="menu-content collapse out">
                          <li onClick = "location.href = \'view_usuarios.php\'">
                            <a href="view_usuarios.php"><i class="fa fa-file-text fa-lg"></i> Usuarios</a>
                          </li>
                      </ul>
                  </div>';
          break;
    }
    return $menu;
  }

  public function getMenuHistorialUsuario($ID){
    switch ($ID) {
      case 21: 
          $menu = '<div class="menu-list">  
            <ul id="menu-content" class="menu-content collapse out">
                <li class="collapsed active" onClick = "location.href = \'para_que_sirve.pdf\'">
                  <a href="para_que_sirve.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Para qué sirve?</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'como_funciona.pdf\'">
                  <a href="como_funciona.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Cómo funciona?</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'licencia.pdf\'">
                  <a href="licencia.pdf"><i class="fa fa-file-text fa-lg"></i> Licencia</a>
                </li>
               
            </ul>
        </div>';
        break;
	   case 22: 
          $menu = '<div class="menu-list">
                  <ul id="menu-content" class="menu-content collapse out">
                      <li class="collapsed" onClick = "location.href = \'para_que_sirve.pdf\'">
                        <a href="para_que_sirve.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Para qué sirve?</a>
                      </li>
                      <li class="collapsed active" onClick = "location.href = \'como_funciona.pdf\'">
                          <a href="como_funciona.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Cómo funciona?</a>
                      </li>
                      <li class="collapsed" onClick = "location.href = \'licencia.pdf\'">
                          <a href="licencia.pdf"><i class="fa fa-file-text fa-lg"></i> Licencia</a>
                      </li>
                    
                  </ul>
              </div>';
          break;
      case 23: 
          $menu = '<div class="menu-list">
                    <ul id="menu-content" class="menu-content collapse out">
                        <li class="collapsed" onClick = "location.href = \'para_que_sirve.pdf\'">
                          <a href="para_que_sirve.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Para qué sirve?</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'como_funciona.pdf\'">
                            <a href="como_funciona.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Cómo funciona?</a>
                        </li>
                        <li class="collapsed active" onClick = "location.href = \'licencia.pdf\'">
                            <a href="licencia.pdf"><i class="fa fa-file-text fa-lg"></i> Licencia</a>
                        </li>
                      
                    </ul>
                </div>';
          break;
      default: 
          $menu = '<div class="menu-list">
                    <ul id="menu-content" class="menu-content collapse out">
                        <li onClick = "location.href = \'para_que_sirve.pdf\'">
                          <a href="para_que_sirve.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Para qué sirve?</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'como_funciona.pdf\'">
                          <a href="como_funciona.pdf"><i class="fa fa-file-text fa-lg"></i> ¿Cómo funciona?</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'licencia.pdf\'">
                          <a href="licencia.pdf"><i class="fa fa-file-text fa-lg"></i> Licencia</a>
                        </li>                
                    </ul>
                </div>';
          break;
    }
    return $menu;
  }

  public function getMenuActualizacionesUsuario($ID){
		switch ($ID) {
			case self::PAGINA_PERSONA:
          $menu =  '<div class="menu-list">
                    <ul id="menu-content" class="menu-content collapse out">
                        <li class="collapsed active" onClick = "location.href = \'view_personas.php\'">
                          <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                          <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                          <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                        </li>  
                        <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                          <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                          <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                          <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                          <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                          <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                        </li>
                        <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                        <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                        </li>
                    </ul>
                   </div>';
            break;
			case self::PAGINA_MOTIVO:
          $menu =  '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
     		</div>';break;
			case self::PAGINA_CATEGORIA:
          $menu =  '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
     		</div>';break;
     		case self::PAGINA_RESPONSABLE:
            $menu =  '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed active" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
     		</div>';break;
     		case self::PAGINA_CENTRO_SALUD:
            $menu =  '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
     		</div>';break;  
        	case self::PAGINA_ESCUELA:
              $menu =  '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
        	</div>';break; 
          case self::PAGINA_OTRAS_INSTITUCIONES:
              $menu =  '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
          </div>';break;        
          case self::PAGINA_BARRIO:
              $menu =  '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed active" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
          </div>';break;
          case self::PAGINA_CALLE:
              $menu =  '<div class="menu-list">
  
          <ul id="menu-content" class="menu-content collapse out">
              <li onClick = "location.href = \'view_personas.php\'">
                <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
              </li>
              <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
              </li>
              <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
              </li>  
              <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
              </li>
              <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
              </li>
              <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
              </li>
              <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
              </li>
              <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
              </li>
              <li class="collapsed active" onClick = "location.href = \'view_calles.php\'">
              <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
            </li>
          </ul>
        </div>';break;
			default:
          $menu = '<div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li onClick = "location.href = \'view_personas.php\'">
                  <a href="view_personas.php"><i class="fa fa-file-text fa-lg"></i> Persona</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_motivos.php\'">
                  <a href="view_motivos.php"><i class="fa fa-file-text fa-lg"></i> Motivo</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_categorias.php\'">
                  <a href="view_categorias.php"><i class="fa fa-file-text fa-lg"></i> Categoría</a>
                </li>  
                <li class="collapsed" onClick = "location.href = \'view_responsables.php\'">
                  <a href="view_responsables.php"><i class="fa fa-file-text fa-lg"></i> Responsable</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_centros.php\'">
                  <a href="view_centros.php"><i class="fa fa-file-text fa-lg"></i> Centros Salud</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_escuelas.php\'">
                  <a href="view_escuelas.php"><i class="fa fa-file-text fa-lg"></i> Escuela</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_otrasinstituciones.php\'">
                  <a href="view_otrasinstituciones.php"><i class="fa fa-file-text fa-lg"></i> Otras Instituciones</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_barrios.php\'">
                  <a href="view_barrios.php"><i class="fa fa-file-text fa-lg"></i> Barrio</a>
                </li>
                <li class="collapsed" onClick = "location.href = \'view_calles.php\'">
                <a href="view_calles.php"><i class="fa fa-file-text fa-lg"></i> Calles</a>
                </li>
            </ul>
     		</div>';
        break;
		}
		return $menu;
	}

//Metodos Get Agentes
  public function CBPersonas(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Persona'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from persona where estado = 1 order by apellido, nombre")or die("Problemas al mostrar Personas");
    $Select .= "<option value = '0' disabled = 'disabled' selected = 'true'>Seleccione una Persona</option>";
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_persona']."'>".$Ret['apellido'].", ".$Ret['nombre']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBModPersonas($xID_Persona){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Persona'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from persona where estado = 1 order by apellido, nombre")or die("Problemas al mostrar Personas");
    $Select .= "<option value = '0' disabled = 'disabled' selected = 'true'>Seleccione una Persona</option>";
    while ($Ret = mysqli_fetch_array($Consulta)) {      
      if($Ret['id_persona'] == $xID_Persona){
        $Select .= "<option value = '".$Ret['id_persona']."' selected>".$Ret['apellido'].", ".$Ret['nombre']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['id_persona']."'>".$Ret['apellido'].", ".$Ret['nombre']."</option>";
      }  
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function BTNModPersonas($xID_Persona){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    //$Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Persona'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from persona where estado = 1 and id_persona = $xID_Persona order by apellido, nombre")or die("Problemas al mostrar Personas");
    $Ret = mysqli_fetch_assoc($Consulta);    
        
    $Boton = "<button type = 'button' class = 'btn btn-lg btn-primary btn-block' data-toggle='modal' data-target='#ModalPersona'>".$Ret['apellido'].", ".$Ret['nombre']."</button>";
    $Con3->CloseConexion();
    return $Boton;
  }

  public function CBPrimeraPersona(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Persona_1'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from persona where estado = 1 order by apellido, nombre")or die("Problemas al mostrar Personas");
    $Select .= "<option value = '0' disabled = 'disabled' selected = 'true'>Seleccione una Persona</option>";
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_persona']."'>".$Ret['apellido'].", ".$Ret['nombre']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBSegundaPersona(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Persona_2'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from persona where estado = 1 order by apellido, nombre")or die("Problemas al mostrar Personas");
    $Select .= "<option value = '0' disabled = 'disabled' selected = 'true'>Seleccione una Persona</option>";
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_persona']."'>".$Ret['apellido'].", ".$Ret['nombre']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBBarrios(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' name = 'ID_Barrio' id = 'ID_Barrio'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from barrios where estado = 1 order by Barrio")or die("Problemas al mostrar Barrios");
    $Select .= "<option value = '0' disabled = 'disabled' selected = 'true'>- Seleccione un Barrio -</option>";
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['ID_Barrio']."'>".$Ret['Barrio']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBModBarrios($xID_Barrio){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' name = 'ID_Barrio' id = 'ID_Barrio'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from barrios where estado = 1 order by Barrio")or die("Problemas al mostrar Barrios");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      if($Ret['ID_Barrio'] == $xID_Barrio){
        $Select .= "<option value = '".$Ret['ID_Barrio']."' selected>".$Ret['Barrio']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['ID_Barrio']."'>".$Ret['Barrio']."</option>";
      }      
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }


  public function CBResponsables(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_Responsable' name = 'ID_Responsable[]'>";
    $Select .= "<option selected = 'true' disabled = 'disabled'>-Seleccione un Responsable-</option>";
    $Responsables = "select * 
                     from responsable 
                     where estado = 1 
                     order by responsable";
    $Consulta = mysqli_query($Con3->Conexion,$Responsables)or die("Problemas al mostrar Responsables");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_resp']."'>".$Ret['responsable']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBModResponsables($xID_Responsable){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Responsable[]'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from responsable where estado = 1 order by responsable")or die("Problemas al mostrar Responsables");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      if($Ret['id_resp'] == $xID_Responsable){
        $Select .= "<option value = '".$Ret['id_resp']."' selected>".$Ret['responsable']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['id_resp']."'>".$Ret['responsable']."</option>";
      }
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBCentros(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_Centro' name = 'ID_Centro'>";
    $Select .= "<option selected = 'true' disabled = 'disabled'>-Seleccione un Centro de Salud-</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from centros_salud where estado = 1 order by centro_salud")or die("Problemas al mostrar Centros de Salud");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_centro']."'>".$Ret['centro_salud']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBModCentros($xID_Centro){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Centro'>";    
    $Consulta = mysqli_query($Con3->Conexion,"select * from centros_salud where estado = 1 order by centro_salud")or die("Problemas al mostrar Centros de Salud");
    while ($Ret = mysqli_fetch_array($Consulta)) {      
      if($Ret['id_centro'] == $xID_Centro){
        $Select .= "<option value = '".$Ret['id_centro']."' selected>".$Ret['centro_salud']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['id_centro']."'>".$Ret['centro_salud']."</option>";
      }   
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBRepModCentros($xID_Centro){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name ='ID_CentroSalud' id ='ID_CentroSalud'>";    
    $Consulta = mysqli_query($Con3->Conexion,"select * from centros_salud where estado = 1 order by centro_salud")or die("Problemas al mostrar Centros de Salud");
    while ($Ret = mysqli_fetch_array($Consulta)) {      
      if($Ret['id_centro'] == $xID_Centro){
        $Select .= "<option value = '".$Ret['id_centro']."' selected>".$Ret['centro_salud']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['id_centro']."'>".$Ret['centro_salud']."</option>";
      }   
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBOtrasInstituciones(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_OtraInstitucion' name = 'ID_OtraInstitucion'>";
    $Select .= "<option selected = 'true' disabled = 'disabled'>-Seleccione una Institución-</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from otras_instituciones where estado = 1 order by Nombre")or die("Problemas al mostrar Otras Instituciones");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['ID_OtraInstitucion']."'>".$Ret['Nombre']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBModOtrasInstituciones($xID_OtraInstitucion){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_OtraInstitucion'>";    
    $Select .= "<option disabled = 'disabled'>-Seleccione una Institución-</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from otras_instituciones where estado = 1 order by Nombre")or die("Problemas al mostrar Otras Instituciones");
    while ($Ret = mysqli_fetch_array($Consulta)) {      
      if($Ret['ID_OtraInstitucion'] == $xID_OtraInstitucion){
        $Select .= "<option value = '".$Ret['ID_OtraInstitucion']."' selected>".$Ret['Nombre']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['ID_OtraInstitucion']."'>".$Ret['Nombre']."</option>";
      }   
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBMotivo_1(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Motivo_1'>";
    $Select .= "<option selected = 'true' disabled = 'disabled'>Seleccione un Motivo</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 and id_motivo > 1 order by motivo")or die("Problemas al mostrar Motivo_1");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_motivo']."'>".$Ret['motivo']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBModMotivo_1($xID){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Motivo_1'>";
    $Select .= "<option selected = 'true' disabled = 'disabled'>Seleccione un Motivo</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 and id_motivo > 1 order by motivo")or die("Problemas al mostrar Motivo_1");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      if($Ret['id_motivo'] == $xID){
        $Select .= "<option value = '".$Ret['id_motivo']."' selected>".$Ret['motivo']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['id_motivo']."'>".$Ret['motivo']."</option>";
      }      
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function BTNModMotivo_1($xID){
    $Con3 = new Conexion();
    $Con3->OpenConexion();        
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 and id_motivo = $xID order by motivo")or die("Problemas al mostrar Personas");    
    $Ret = mysqli_fetch_assoc($Consulta);    
        
    $Boton = "<button type = 'button' class = 'btn btn-lg btn-primary btn-block' data-toggle='modal' data-target='#ModalMotivo_1'>".$Ret['motivo']."</button>";
    $Con3->CloseConexion();
    return $Boton;
  }

  public function CBMotivo_2(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Motivo_2'>";
    $Select .= "<option selected = 'true' disabled = 'disabled'>Seleccione un Motivo</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 and id_motivo > 1 order by motivo")or die("Problemas al mostrar Motivo_2");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_motivo']."'>".$Ret['motivo']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }  

  public function CBModMotivo_2($xID){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Motivo_2'>";
    $Select .= "<option selected = 'true' disabled = 'disabled'>Seleccione un Motivo</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 and id_motivo > 1 order by motivo")or die("Problemas al mostrar Motivo_2");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      if($Ret['id_motivo'] == $xID){
        $Select .= "<option value = '".$Ret['id_motivo']."' selected>".$Ret['motivo']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['id_motivo']."'>".$Ret['motivo']."</option>";
      }      
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function BTNModMotivo_2($xID){
    $Con3 = new Conexion();
    $Con3->OpenConexion();    
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 and id_motivo = $xID order by motivo")or die("Problemas al mostrar Personas");    
    $Ret = mysqli_fetch_assoc($Consulta);    
        
    $Boton = "<button type = 'button' class = 'btn btn-lg btn-primary btn-block' data-toggle='modal' data-target='#ModalMotivo_2'>".$Ret['motivo']."</button>";
    $Con3->CloseConexion();
    return $Boton;
  }

  public function CBMotivo_3(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Motivo_3'>";
    $Select .= "<option selected = 'true' disabled = 'disabled'>Seleccione un Motivo</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 and id_motivo > 1 order by motivo")or die("Problemas al mostrar Motivo_3");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_motivo']."'>".$Ret['motivo']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }  

  public function CBModMotivo_3($xID){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Motivo_3'>";
    $Select .= "<option selected = 'true' disabled = 'disabled'>Seleccione un Motivo</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 and id_motivo > 1 order by motivo")or die("Problemas al mostrar Motivo_3");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      if($Ret['id_motivo'] == $xID){
        $Select .= "<option value = '".$Ret['id_motivo']."' selected>".$Ret['motivo']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['id_motivo']."'>".$Ret['motivo']."</option>";
      }      
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function BTNModMotivo_3($xID){
    $Con3 = new Conexion();
    $Con3->OpenConexion();    
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 and id_motivo = $xID order by motivo")or die("Problemas al mostrar Personas");    
    $Ret = mysqli_fetch_assoc($Consulta);    
        
    $Boton = "<button type = 'button' class = 'btn btn-lg btn-primary btn-block' data-toggle='modal' data-target='#ModalMotivo_3'>".$Ret['motivo']."</button>";
    $Con3->CloseConexion();
    return $Boton;
  }

  public function BTNModMotivo_4($xID){
    $Con3 = new Conexion();
    $Con3->OpenConexion();    
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 and id_motivo = $xID order by motivo")or die("Problemas al mostrar Personas");    
    $Ret = mysqli_fetch_assoc($Consulta);    
        
    $Boton = "<button type = 'button' class = 'btn btn-lg btn-primary btn-block' data-toggle='modal' data-target='#ModalMotivo_4'>".$Ret['motivo']."</button>";
    $Con3->CloseConexion();
    return $Boton;
  }

  public function BTNModMotivo_5($xID){
    $Con3 = new Conexion();
    $Con3->OpenConexion();    
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 and id_motivo = $xID order by motivo")or die("Problemas al mostrar Personas");    
    $Ret = mysqli_fetch_assoc($Consulta);    
        
    $Boton = "<button type = 'button' class = 'btn btn-lg btn-primary btn-block' data-toggle='modal' data-target='#ModalMotivo_5'>".$Ret['motivo']."</button>";
    $Con3->CloseConexion();
    return $Boton;
  }

  public function CBPrimerMotivo(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Motivo_1'>";
    $Select .= "<option selected = 'true' disabled = 'disabled'>Seleccione un Motivo</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 order by motivo")or die("Problemas al mostrar Motivo_1");

    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_motivo']."'>".$Ret['motivo']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBSegundoMotivo(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Motivo_2'>";
    $Select .= "<option selected = 'true' disabled = 'disabled'>Seleccione un Motivo</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where estado = 1 order by motivo")or die("Problemas al mostrar Motivo_1");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_motivo']."'>".$Ret['motivo']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBCategoria(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_Categoria' name = 'ID_Categoria'>";
    $Select .= "<option selected = 'true' disabled = 'disabled' value = '0'>-Seleccione una Categoría-</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from categoria where estado = 1 order by categoria")or die("Problemas al mostrar Categoría");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_categoria']."'>".$Ret['categoria']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBModCategoria($xID){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Categoria'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from categoria where estado = 1 order by categoria")or die("Problemas al mostrar Categoría");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      if($Ret['id_categoria'] == $xID){
        $Select .= "<option value = '".$Ret['id_categoria']."' selected>".$Ret['categoria']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['id_categoria']."'>".$Ret['categoria']."</option>";
      }      
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBRepPersonas(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Persona'>";
    $Select .= "<option value = '0'>-Todos-</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from persona where estado = 1 order by apellido, nombre")or die("Problemas al mostrar Personas");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_persona']."'>".$Ret['apellido'].", ".$Ret['nombre']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBRepBarrios(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Barrio[]'>";
    $Select .= "<option value = '0'>-Todos-</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from barrios where estado = 1 order by Barrio")or die("Problemas al mostrar Barrios");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['ID_Barrio']."'>".$Ret['Barrio']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBRepEscuelas(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_Escuela' name = 'ID_Escuela'>";
    $Select .= "<option value = '0'>-Todos-</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from escuelas order by Escuela")or die("Problemas al mostrar Escuelas");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      if($Ret["ID_Nivel"] != null || $Ret["ID_Nivel"] != ""){
        $ID_Nivel = $Ret["ID_Nivel"];
        $ConsultarNivel = mysqli_query($Con3->Conexion,"select Nivel from nivel_escuelas where ID_Nivel = $ID_Nivel limit 1") or die("Problemas al Mostrar Niveles de Escuelas");
        $RetNivel = mysqli_fetch_assoc($ConsultarNivel);      
        $RetNivel = "(".$RetNivel["Nivel"].")";
      }else{
        $RetNivel = "";
      }    
      
      $Select .= "<option value = '".$Ret['ID_Escuela']."'>".$Ret['Escuela']." ".$RetNivel."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBRepMotivo(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Motivo'>";
    $Select .= "<option value = '0'>-Todos-</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from motivo where id_motivo > 1 and estado = 1 order by motivo")or die("Problemas al mostrar Motivo_1");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_motivo']."'>".$Ret['motivo']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBRepCategoria(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Categoria'>";
    $Select .= "<option value = '0'>-Todos-</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from categoria where estado = 1 order by categoria")or die("Problemas al mostrar Categoría");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_categoria']."'>".$Ret['categoria']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBRepResponsable(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name = 'ID_Responsable'>";
    $Select .= "<option value = '0'>-Todos-</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from responsable where estado = 1 order by responsable")or die("Problemas al mostrar Categoría");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_resp']."'>".$Ret['responsable']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBRepCentros(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_Centro' name = 'ID_CentroSalud'>";
    $Select .= "<option value = '0'>-Todos-</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from centros_salud where estado = 1 order by centro_salud")or die("Problemas al mostrar Centros de Salud");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['id_centro']."'>".$Ret['centro_salud']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBRepOtrasInstituciones(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_OtraInstitucion' name = 'ID_OtraInstitucion'>";
    $Select .= "<option value = '0'>-Todos-</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from otras_instituciones where estado = 1 order by Nombre")or die("Problemas al mostrar Otras Instituciones");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['ID_OtraInstitucion']."'>".$Ret['Nombre']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBRepModOtrasInstituciones($xID_OtraInstitucion){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name ='ID_OtraInstitucion' id ='ID_OtraInstitucion'>";    
    $Consulta = mysqli_query($Con3->Conexion,"select * from otras_instituciones where estado = 1 order by Nombre")or die("Problemas al mostrar Otras Instituciones");
    while ($Ret = mysqli_fetch_array($Consulta)) {      
      if($Ret['ID_OtraInstitucion'] == $xID_OtraInstitucion){
        $Select .= "<option value = '".$Ret['ID_OtraInstitucion']."' selected>".$Ret['Nombre']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['ID_OtraInstitucion']."'>".$Ret['Nombre']."</option>";
      }   
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBRepModResponsables($xID_Responsable){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='exampleFormControlSelect1' name ='ID_Responsable' id ='ID_Responsable'>";    
    $Consulta = mysqli_query($Con3->Conexion,"select * from responsable where estado = 1 order by responsable")or die("Problemas al mostrar Responsables");
    while ($Ret = mysqli_fetch_array($Consulta)) {      
      if($Ret['id_resp'] == $xID_Responsable){
        $Select .= "<option value = '".$Ret['id_resp']."' selected>".$Ret['responsable']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['id_resp']."'>".$Ret['responsable']."</option>";
      }   
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }
  //////////////////////////////////////////////// TIPO DE USUARIOS ////////////////////////////////////////////////////

  public function CBTipoUsuarios(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_TipoUsuario' name = 'ID_TipoUsuario'>";
    $Select .= "<option selected = 'true' disabled = 'disabled' value = '0'>- Seleccione un Permiso -</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from Tipo_Usuarios order by ID_TipoUsuario")or die("Problemas al mostrar Tipo de Usuarios");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['ID_TipoUsuario']."'>".$Ret['descripcion']." ( ".$Ret['abreviacion']. " )"."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }
  public function CBTipoUsuariosID($ID){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_TipoUsuario' name = 'ID_TipoUsuario'>";
    $Select .= "<option selected = 'true' disabled = 'disabled' value = '0'>- Seleccione un Permiso -</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from Tipo_Usuarios order by ID_TipoUsuario")or die("Problemas al mostrar Tipo de Usuarios");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      if($Ret['ID_TipoUsuario'] == $ID){
        $Select .= "<option SELECTED value = '".$Ret['ID_TipoUsuario']."'>".$Ret['descripcion']." ( ".$Ret['abreviacion']. " )"."</option>";
      } else {
        $Select .= "<option value = '".$Ret['ID_TipoUsuario']."'>".$Ret['descripcion']." ( ".$Ret['abreviacion']. " )"."</option>";
      }
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }


  ////////////////////////////////////////////// ESCUELAS //////////////////////////////////////////////////////////////////
  public function CBNivelEscuelas(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_Nivel' name = 'ID_Nivel' onChange='CargarEscuelas(this.value)'>";
    $Select .= "<option selected = 'true' disabled = 'disabled' value = '0'>- Seleccione un Nivel Escolar -</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from nivel_escuelas order by ID_Nivel")or die("Problemas al mostrar el Nivel de Escuelas");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['ID_Nivel']."'>".$Ret['Nivel']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBModNivelEscuelas($xNivel){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_Nivel' name = 'ID_Nivel'>";
    $Select .= "<option selected = 'true' disabled = 'disabled' value = '0'>- Seleccione un Nivel Escolar -</option>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from nivel_escuelas order by ID_Nivel")or die("Problemas al mostrar el Nivel de Escuelas");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      if($Ret["ID_Nivel"] == $xNivel){
        $Select .= "<option value = '".$Ret['ID_Nivel']."' selected>".$Ret['Nivel']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['ID_Nivel']."'>".$Ret['Nivel']."</option>";
      }      
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }


  public function CBEscuelas($xID_Nivel){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_Escuela' name = 'ID_Escuela'>";
    if($xID_Nivel == 0){
      $Select .= "<option selected = 'true' disabled = 'disabled' value = '0'>- Debe seleccionar primero un Nivel Escolar -</option>";  
    }else{
      $Select .= "<option selected = 'true' disabled = 'disabled' value = '0'>- Seleccione una Escuela -</option>";
    }    
    $SQLQuery = "select * from escuelas where ID_Nivel = {$xID_Nivel} order by Escuela";
    $Consulta = mysqli_query($Con3->Conexion,$SQLQuery)or die("Problemas al mostrar Escuelas".$SQLQuery);
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['ID_Escuela']."'>".$Ret['Escuela']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBModEscuelas($xID_Escuela){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' name = 'ID_Escuela' id = 'ID_Escuela'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from escuelas order by Escuela")or die("Problemas al mostrar Escuelas");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      if($Ret['ID_Escuela'] == $xID_Escuela){
        $Select .= "<option value = '".$Ret['ID_Escuela']."' selected>".$Ret['Escuela']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['ID_Escuela']."'>".$Ret['Escuela']."</option>";
      }      
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  ///////////////////////////////////////////// CATEGORIAS ///////////////////////////////////////////////////////
  public function CBFormas_Categoria(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' name = 'ID_Forma' id = 'ID_Forma'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from formas_categorias order by Figura")or die("Problemas al mostrar las Formas de las Categorías");
    while ($Ret = mysqli_fetch_array($Consulta)) {      
        $Select .= "<option value = '".$Ret['ID_Forma']."'>".$Ret['Forma_Categoria']."</option>";
    }          
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBModFormas_Categoria($xID_Forma){
  	$Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' name = 'ID_Forma' id = 'ID_Forma'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from formas_categorias order by Figura")or die("Problemas al mostrar Formas de Categorías");
    while ($Ret = mysqli_fetch_array($Consulta)) {
      if($Ret['ID_Forma'] == $xID_Forma){
        $Select .= "<option value = '".$Ret['ID_Forma']."' selected>".$Ret['Forma_Categoria']."</option>";
      }else{
        $Select .= "<option value = '".$Ret['ID_Forma']."'>".$Ret['Forma_Categoria']."</option>";
      }      
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBTipos_Usuario(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control'  multiple='multiple' name = 'Tipo_Usuario[]' id = 'Tipo_Usuario'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from Tipo_Usuarios order by TipoUsuario")or die("Problemas al mostrar las Formas de las Categorías");
    while ($Ret = mysqli_fetch_array($Consulta)) {      
        $Select .= "<option value = '".$Ret['ID_TipoUsuario']."'>".$Ret['descripcion']."</option>";
    }          
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBCategorias_Roles_ID($XID){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control'  multiple='multiple' name = 'Tipo_Usuario[]' id = 'Tipo_Usuario'>";
    $ConsultaSQL = "select tip.ID_TipoUsuario, tip.descripcion, IF(cs.ID_TipoUsuario IS NULL,true ,false) as disable
                          from (SELECT * FROM categorias_roles where id_categoria = {$XID} and estado = 1) cs  
                                right join Tipo_Usuarios tip on cs.id_tipousuario = tip.ID_TipoUsuario";
    $Consulta = mysqli_query($Con3->Conexion, $ConsultaSQL) or die("Problemas al mostrar las Formas de las Categorías");
    while ($Ret = mysqli_fetch_array($Consulta)) {
        if( $Ret["disable"] != "1"){
          $Select .= "<option selected value = '".$Ret['ID_TipoUsuario']."'>".$Ret['descripcion']."</option>"; 
        } else {
          $Select .= "<option value = '".$Ret['ID_TipoUsuario']."'>".$Ret['descripcion']."</option>"; 
        }
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }
  ////////////////////////////////////////// CALLES ///////////////////////////////////////////////////
  public function CBCalles(){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $Select = "<select class='form-control' id='ID_Cale' name = 'Calle'>";
    $Consulta = mysqli_query($Con3->Conexion,"select * from calle where estado = 1 order by calle_nombre ASC")or die("Problemas al mostrar Personas");
    $Select .= "<option value = '0' disabled = 'disabled' selected = 'true'>- Seleccione una Calle -</option>";
    while ($Ret = mysqli_fetch_array($Consulta)) {
      $Select .= "<option value = '".$Ret['calle_nombre']."'>".$Ret['calle_nombre']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  public function CBCallesNombre($Nombre){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $NombreCalle = rtrim($Nombre);
    $Select = "<select class='form-control' id='ID_Cale' name = 'Calle'>";

    if (!is_numeric($Nombre)) {
      $ConsultaNombre = "select *
                         from calle 
                         where estado = 1
                         and UPPER(calle_nombre) like UPPER('%$NombreCalle%')
                         order by calle_nombre ASC";               
  
      $ConsultaResult = mysqli_query($Con3->Conexion,$ConsultaNombre)or die("Problemas al mostrar Personas");
    } else {
      $ConsultaNombre = "select *
                         from calle 
                         where estado = 1
                         and id_calle = $NombreCalle
                         order by calle_nombre ASC";               
  
      $ConsultaResult = mysqli_query($Con3->Conexion,$ConsultaNombre)or die("Problemas al mostrar Personas");
    }

    if(mysqli_num_rows($ConsultaResult) > 0){
      $Resultado = mysqli_fetch_array($ConsultaResult);
      $Select .= "<option value = '" . $Resultado["id_calle"] . "' selected = 'true'>" . $Resultado["calle_nombre"] . "</option>";
    } else {
        $Select .= "<option value = '0' disabled = 'disabled' selected = 'true'>- Seleccione una Calle -</option>";
    }
    $Consulta = "select *
                 from calle 
                 where estado = 1
                 order by calle_nombre ASC";
    $ConsultaResult = mysqli_query($Con3->Conexion,$Consulta)or die("Problemas al mostrar Personas");
    while ($Ret = mysqli_fetch_array($ConsultaResult)) {
      $Select .= "<option value = '".$Ret['id_calle']."'>".$Ret['calle_nombre']."</option>";
    }
    $Select .= "</select>";
    $Con3->CloseConexion();
    return $Select;
  }

  /////////////////////////////////////// MENU DE NAVEGACION /////////////////////////////////////////
  public function CBSessionNombre($idAccount){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $ConsultaNombre = "select *
                       from accounts 
                       where estado = 1
                         and accountid = $idAccount";
    $ConsultaResult = mysqli_query($Con3->Conexion,$ConsultaNombre)or die("Problemas al mostrar Personas");
    if(mysqli_num_rows($ConsultaResult) > 0){
      $Resultado = mysqli_fetch_array($ConsultaResult);
      $cardSession = "<div class='title-session'>".
                        $Resultado["username"] . "
                      </div>";
    } else {
      $cardSession = "<div class='letras-circle'> Login </div>";
    }
    $Con3->CloseConexion();
    echo $cardSession;
  }

  public function CBSessionNombreUsuario($idAccount){
    $Con3 = new Conexion();
    $Con3->OpenConexion();
    $ConsultaNombre = "select *
                       from accounts 
                       where estado = 1
                         and accountid = $idAccount";
    $ConsultaResult = mysqli_query($Con3->Conexion,$ConsultaNombre)or die("Problemas al mostrar Personas");
    if(mysqli_num_rows($ConsultaResult) > 0){
      $Resultado = mysqli_fetch_array($ConsultaResult);
      $cardSession = "<div class='title-session'>
                        <a id='perfil' style='color: #e1ffff' href='view_perfilusuario.php?account_id=" . $idAccount . "'>".
                          $Resultado["username"] . "
                        </a>
                      </div>";
    } else {
      $cardSession = "<div class='letras-circle'> Login </div>";
    }
    $Con3->CloseConexion();
    return $cardSession;
  }

  public function menuDeNavegacion($TipoUsuario, $ID_Usuario, $pagina) 
  {
    if ($TipoUsuario == 1) {
      $menu = "<div class='col-md-3'>
                  <div class='nav-side-menu'>" . 
                    self::CBSessionNombreUsuario($ID_Usuario) . 
                    "<div class='brand'>General</div>
                       <i class='fa fa-bars fa-2x toggle-btn' data-toggle='collapse' data-target='#menu-content'></i>
                          <div class='menu-list'>" .        
                            self::getMenuGeneralUsuario($pagina) . 
                          "</div>
                          <div class='brand'>Actualizaciones</div>
                          <div class='menu-list'>" . 
                            self::getMenuActualizacionesUsuario($pagina) . 
                          "</div>
                          <div class='brand'>Reportes</div>
                          <div class='menu-list'>" .
                            self::getMenuReportesUsuario($pagina) . 
                          "</div>
                          <div class='brand'>Unificación</div>
                          <div class='menu-list'>" . 
                            self::getMenuUnificacionUsuario($pagina) . 
                          "</div>
                          <div class='brand'>Seguridad</div>
                          <div class='menu-list'>" .
                              self::getMenuSeguridadUsuario($pagina) . 
                          "</div>
                          <div class='brand'>El Proyecto</div>
                          <div class='menu-list'>" . 
                              self::getMenuHistorialUsuario($pagina) . 
                          "</div>
                          <div class='brand btn-Salir' onClick=\"location.href = 'Controladores/CtrLogout.php'\">Salir</div>
                      </div>
                    </div>";
    } elseif ($TipoUsuario == 2 || $TipoUsuario > 3) {
          $menu = "<div class = 'col-md-3'>
                      <div class='nav-side-menu'>" . 
                        self::CBSessionNombreUsuario($ID_Usuario) . 
                        "<div class='brand'>General</div>
                          <i class='fa fa-bars fa-2x toggle-btn' data-toggle='collapse' data-target='#menu-content'></i>
                              <div class='menu-list'>" . 
                                self::getMenuGeneralUsuario($pagina) . 
                              "</div>
                              <div class='brand'>Actualizaciones</div>
                              <div class='menu-list'>" . 
                                self::getMenuActualizacionesUsuario($pagina) .
                              "</div>
                              <div class='brand'>Reportes</div>
                              <div class='menu-list'>" . 
                                self::getMenuReportesUsuario($pagina) . 
                              "</div>
                              <div class='brand'>El Proyecto</div>
                              <div class='menu-list'>" . 
                                self::getMenuHistorialUsuario($pagina) . 
                              "</div>
                              <div class='brand btn-Salir' onClick=\"location.href='Controladores/CtrLogout.php'\">Salir</div>
                          </div>
                        </div>";
    } elseif ($TipoUsuario == 3) {
      $menu = "<div class = 'col-md-3'>
                <div class='nav-side-menu'>" . 
                      self::CBSessionNombreUsuario($ID_Usuario) . 
                    "<div class='brand'>General</div>
                    <i class='fa fa-bars fa-2x toggle-btn' data-toggle='collapse' data-target='#menu-content'></i>
                        <div class='menu-list'>" . 
                            self::getMenuGeneralUsuario($pagina) . 
                        "</div>
                        <div class='brand'>Actualizaciones</div>
                        <div class='menu-list'>" . 
                          self::getMenuActualizacionesUsuario($pagina) . 
                        "</div>
                        <div class='brand'>Reportes</div>
                        <div class='menu-list'>" . 
                            self::getMenuReportesUsuario($pagina) . 
                        "</div>
                        <div class='brand'>Unificación</div>
                        <div class='menu-list'>" . 
                          self::getMenuUnificacionUsuario($pagina) . 
                        "</div>
                        <div class='brand'>El Proyecto</div>
                        <div class='menu-list'>" . 
                            self::getMenuHistorialUsuario($pagina) .
                        "</div>
                        <div class='brand btn-Salir' onClick = \"location.href = 'Controladores/CtrLogout.php'\">Salir</div>
                    </div>
                  </div>";
    }
    return $menu;
  }

  /*
 *
 * This file is part of Rastreador3.
 *
 * Rastreador3 is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Rastreador3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Rastreador3; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 */

}
