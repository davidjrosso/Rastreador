import swal from 'sweetalert2';

export class FormularioReporte {

    #cantBarrios = 1;
    #cantMotivos = 1;
    #cantResponsable = 1;
    #listaMotivos = new Map();
    #listaCategorias = new Map();
    #cantCategoria = 1;


    getListaMotivos() {
      return this.#listaMotivos;
    }

    agregarBarrio() {
      this.#cantBarrios++;
      let divContenedor = document.getElementById('contenedorBarrios');
      let selectBarrio = document.getElementById('ID_Barrio');
      let divBarrio = document.createElement("div");
      divBarrio.setAttribute('class','form-group row');
      let labelBarrio = document.createElement("label");
      labelBarrio.setAttribute('class','col-md-2 col-form-label LblForm');
      labelBarrio.innerText = 'Barrio ' + this.#cantBarrios + ':';
      let divSelectBarrio = document.createElement("div");
      divSelectBarrio.setAttribute('class','col-md-10');
      let select = selectBarrio.cloneNode(true);
      divSelectBarrio.appendChild(select);
      divBarrio.appendChild(labelBarrio);
      divBarrio.appendChild(divSelectBarrio);
      divContenedor.appendChild(divBarrio);
    }

    addMultipleMotivo(xMotivo, xID, element) {
      if (!this.#listaMotivos.has(xMotivo) && (this.#listaMotivos.size <= 4)) {
        this.#listaMotivos.set(xMotivo, xID);
        element.innerHTML = "&#10003";
        element.style.width = "12ch";
      } else if (this.#listaMotivos.has(xMotivo)) {
        this.#listaMotivos.delete(xMotivo);
        element.innerHTML = "seleccionar";
      }
    }

    addMultipleCategoria(xCategoria, xID, element) {
      if (!this.#listaCategorias.has(xCategoria) && (this.#listaCategorias.size <= 7)) {
        this.#listaCategorias.set(xCategoria, xID);
        element.innerHTML = "&#10003";
        element.style.width = "12ch";
      } else if (this.#listaCategorias.has(xCategoria)){
        this.#listaCategorias.delete(xCategoria);
        element.innerHTML = "seleccionar";
      }
    }

    seleccionMultipleMotivo() {
      let motivoNumero = 1;
      let idMotivo = null;
      this.#listaMotivos.forEach((value, key, map) => {
          idMotivo = value;
          if (motivoNumero <= 1) {
            if (motivoNumero == 1) {
              $("#Motivo").html("<p>" + key + "<button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalMotivo" + motivoNumero + "'><i class='fa fa-cog text-secondary'></i></button></p>");
              $("#ID_Motivo").val(idMotivo);
            } else {
              $("#Motivo" + motivoNumero).html("<p>" + key + " <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalMotivo" + motivoNumero + "'><i class='fa fa-cog text-secondary'></i></button></p>");
              $("#ID_Motivo" + motivoNumero).val(idMotivo);
            }
          } else {
            this.agregarMotivo();
            $("#Motivo" + motivoNumero).html("<p>" + key + " <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalMotivo" + motivoNumero + "'><i class='fa fa-cog text-secondary'></i></button></p>");
            $("#ID_Motivo" + motivoNumero).val(idMotivo);
          }
          motivoNumero++;
      });
      for (let index = motivoNumero; index <= 5; index++) {
        if (index == 1) {
          $("#Motivo").html("<button class='btn btn-lg btn-primary btn-block' type='button' data-toggle='modal' data-target='#ModalMotivo'>Seleccione Motivo</button>");
          $("#ID_Motivo").val(null);
        } else {
          $("#Motivo" + index).html("<button class='btn btn-lg btn-primary btn-block' type='button' data-toggle='modal' data-target='#ModalMotivo" + index + "'>Seleccione Motivo</button>");
          $("#ID_Motivo" + index).val(null);
        }
        
      }
    }

    seleccionMultipleCategoria() {
      let categoriaNumero = 1;
      let idCategoria = null;
      this.#listaCategorias.forEach((value, key, map) => {
          idCategoria = value;
          if (categoriaNumero <= 1) {
            if (categoriaNumero == 1) {
              $("#Categoria").html("<p>" + key + "<button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalCategoria'><i class='fa fa-cog text-secondary'></i></button></p>");
              $("#ID_Categoria").val(idCategoria);
            } else {
              $("#Categoria" + categoriaNumero).html("<p>" + key + " <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalCategoria'><i class='fa fa-cog text-secondary'></i></button></p>");
              $("#ID_Categoria" + categoriaNumero).val(idCategoria);
            }
          } else {
            this.agregarCategoria();
            $("#Categoria" + categoriaNumero).html("<p>" + key + " <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalCategoria'><i class='fa fa-cog text-secondary'></i></button></p>");
            $("#ID_Categoria" + categoriaNumero).val(idCategoria);
          }
          categoriaNumero++;
      });
      for (let index = categoriaNumero; index <= 8; index++) {
        if (index == 1) {
          $("#Categoria").html("<button class='btn btn-lg btn-primary btn-block' type='button' data-toggle='modal' data-target='#ModalCategoria'>Seleccione Categoría</button>");
          $("#ID_Categoria").val(null);
        } else {
          $("#Categoria" + index).html("<button class='btn btn-lg btn-primary btn-block' type='button' data-toggle='modal' data-target='#ModalCategoria'>Seleccione Categoría</button>");
          $("#ID_Categoria" + index).val(null);
        }
      }

      $("#SearchCategorias").val("");
      $("#ResultadosCategorias").html("");
    }

    seleccionCategoria(xCategoria, xID){
      let Categoria = document.getElementById("Categoria");
      let ID_Categoria = document.getElementById("ID_Categoria");
      Categoria.innerHTML = "";
      Categoria.innerHTML = "<p>"+xCategoria+"</p>";
      ID_Categoria.setAttribute('value',xID);
    }

    agregarMotivo() {
      if (this.#cantMotivos <= 7) {
        this.#cantMotivos++;
        let divContenedor = document.getElementById('contenedorMotivos');
        let divMotivo = document.createElement("div");
        divMotivo.setAttribute('class','form-group row');
        let labelMotivo = document.createElement("label");
        labelMotivo.setAttribute('class','col-md-2 col-form-label LblForm');
        labelMotivo.innerText = 'Motivo '+ this.#cantMotivos +':';
        let divBotonMotivo = document.createElement("div");
        divBotonMotivo.setAttribute("id", "Motivo" + this.#cantMotivos);
        divBotonMotivo.setAttribute('class','col-md-10');
        let boton = "<button type = 'button' class = 'btn btn-lg btn-primary btn-block' data-toggle='modal' data-target='#ModalMotivo" + this.#cantMotivos + "'>Seleccione Motivo</button>";
        divBotonMotivo.innerHTML = boton;      
        divMotivo.appendChild(labelMotivo);
        divMotivo.appendChild(divBotonMotivo);
        divContenedor.appendChild(divMotivo);
        let divInputsGenerales = document.getElementById('InputsGenerales');
        let divInput = document.createElement("input");
        divInput.setAttribute("id", "ID_Motivo" + this.#cantMotivos);
        divInput.setAttribute("name", "ID_Motivo" + this.#cantMotivos);
        divInput.setAttribute("type", "hidden");
        divInput.setAttribute("data-pre", "1");
        divInputsGenerales.appendChild(divInput);
      }
    }

    agregarResponsable() {
      if (this.#cantResponsable <= 3) {
        this.#cantResponsable++;
        let divContenedor = document.getElementById('responsables');
        let divResponsable = document.getElementById("ID_Responsable");
        let obj = divResponsable.cloneNode(true);
        obj.setAttribute('name', 'ID_Responsable[]');
        let label = document.createElement("label");
        label.setAttribute('class','col-md-2 col-form-label LblForm');
        label.innerText = 'Responsable '+ this.#cantResponsable +':';
        let div = document.createElement("div");
        div.setAttribute('class','col-md-10');
        let divForm = document.createElement("div");
        divForm.setAttribute('class','form-group row');
        divForm.appendChild(label);
        divForm.appendChild(div);
        div.appendChild(obj);
        divContenedor.appendChild(divForm);
      }
    }

    agregarCategoria() {
      if (this.#cantCategoria <= 7) {
        this.#cantCategoria++;
        let divContenedor = document.getElementById('contenedorCategoria');
        let divCategoria = document.createElement("div");
        divCategoria.setAttribute('class','form-group row');
        let labelCategoria = document.createElement("label");
        labelCategoria.setAttribute('class','col-md-2 col-form-label LblForm');
        labelCategoria.innerText = 'Categoria '+ this.#cantCategoria +':';
        let divBotonCategoria = document.createElement("div");
        divBotonCategoria.setAttribute("id", "Categoria" + this.#cantCategoria);
        divBotonCategoria.setAttribute('class','col-md-10');
        let boton = "<button type = 'button' class = 'btn btn-lg btn-primary btn-block' data-toggle='modal' data-target='#ModalCategoria'>Seleccione un Categoria</button>";
        divBotonCategoria.innerHTML = boton;      
        divCategoria.appendChild(labelCategoria);
        divCategoria.appendChild(divBotonCategoria);
        divContenedor.appendChild(divCategoria);
        let divInputsGenerales = document.getElementById('InputsGenerales');
        let divInput = document.createElement("input");
        divInput.setAttribute("id", "ID_Categoria" + this.#cantCategoria);
        divInput.setAttribute("name", "ID_Categoria" + this.#cantCategoria);
        divInput.setAttribute("type", "hidden");
        divInput.setAttribute("data-pre", "1");
        divInputsGenerales.appendChild(divInput);
      }
    }
}