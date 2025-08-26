<!DOCTYPE html>
<html>
<head>
	<title>Inicio</title>
	<link rel="stylesheet" type="text/css" href="css/Estilos.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->

</head>
<body>
<div class = "row">
	<div class = "col-4">
		<div class="nav-side-menu">
    <div class="brand">Combustibles</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li>
                  <a href="#"><i class="fa fa-file-text fa-lg"></i> Facturas</a>
                </li>
                <li class="collapsed">
                  <a href="#"><i class="fa fa-user fa-lg"></i> Agentes</a>
                </li>
                <li class="collapsed active">
                  <a href="#"><i class="fa fa-car fa-lg"></i> Vehiculos</a>
                </li>  
                <li class="collapsed">
                  <a href="#"><i class="fa fa-cube fa-lg"></i> Proveedores</a>
                </li>
                <li class="collapsed">
                  <a href="#"><i class="fa fa-tachometer fa-lg"></i> Combustibles</a>
                </li>
            </ul>
     </div>
</div>
	</div>
	<div class = "col-8">
	   <div class = "container">
      <div class = "col-8">
           <!-- Carga -->
          <p class = "Titulos">Cargar Nuevo Vehiculo</p>
          <form method = "post" action = "#">
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Numero: </label>
              <div class="col-md-10">
                <input type="password" class="form-control" name = "Numero" id="inputPassword" placeholder="Name" width="100%">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Dominio: </label>
              <div class="col-md-10">
                <input type="password" class="form-control" name = "Dominio" id="inputPassword" placeholder="Last Name" width="100%">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Descripcion: </label>
              <div class="col-md-10">
                <input type="password" class="form-control" name = "Descripcion" id="inputPassword" placeholder="N° Leg" width="100%">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Modelo: </label>
              <div class="col-md-10">
                <input type="password" class="form-control" name = "Modelo" id="inputPassword" placeholder="N° Leg" width="100%">
              </div>
            </div>
            <div class="form-group row">
              <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Area: </label>
              <div class = "col-md-10">
                <select class="form-control" id="exampleFormControlSelect1" name = "Area">
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="offset-md-2 col-md-10">
                <button type="submit" class="btn btn-outline-success">Guardar</button>
              </div>
            </div>
          </form>
          <br><br><br>
          <!-- Fin Carga -->
          <!-- Search -->
          <p class = "Titulos">Buscar Vehiculo</p>
          <form class="form-inline">
            <div class="form-group mb-2">
               <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Buscar: </label>
            </div>
            <div class="form-group mx-sm-2 mb-2">
              <input type="text" class="form-control" id="inputPassword2" placeholder="Filtro" name = "Filtro">
            </div>
            <button type="submit" class="btn btn-primary mb-2"><span class="fa fa-search fa-lg"></span>  IR</button>
          </form>
        </div>
     </div>
	</div>
</div>

</body>
</html> 