import swal from 'sweetalert2';


export class Formulario {
    cantMotivos;
    cantResponsables;

    constructor (numMotivos, numResponsable) {
        this.cantMotivos = numMotivos;
        this.cantResponsables = numResponsable;
    }

    addMotivo() {
        if (this.cantMotivos <= 4) {
            this.cantMotivos++;
            let divContenedor = document.getElementById('contenedorMotivos');
            let divMotivo = document.createElement("div");
            divMotivo.setAttribute('class', 'form-group row');
            let labelMotivo = document.createElement("label");
            labelMotivo.setAttribute('class', 'col-md-2 col-form-label LblForm');
            labelMotivo.innerText = 'Motivo ' + this.cantMotivos + ':';
            let divBotonMotivo = document.createElement("div");
            divBotonMotivo.setAttribute("id", "Motivo_" + this.cantMotivos);
            divBotonMotivo.setAttribute('class', 'col-md-10');
            let boton = "<button type = 'button' class = 'btn btn-lg btn-primary btn-block' data-toggle='modal' data-target='#ModalMotivo_" + this.cantMotivos + "'>Seleccione un Motivo</button>";
            divBotonMotivo.innerHTML = boton;
            divMotivo.appendChild(labelMotivo);
            divMotivo.appendChild(divBotonMotivo);
            divContenedor.appendChild(divMotivo);
            let divInputsGenerales = document.getElementById('InputsGenerales');
            let divInput = document.createElement("input");
            divInput.setAttribute("id", "ID_Motivo_" + this.cantMotivos);
            divInput.setAttribute("name", "ID_Motivo_" +this.cantMotivos);
            divInput.setAttribute("type", "hidden");
            divInputsGenerales.appendChild(divInput);
        }
    }

    addResponsable() {
        this.cantResponsables++;
        if(this.cantResponsables < 5){
            let divContenedor = document.getElementById('contenedorResponsables');
            let divResponsables= document.createElement("div");
            divResponsables.setAttribute('class','form-group row');
            let labelResponsables= document.createElement("label");
            labelResponsables.setAttribute('class','col-md-2 col-form-label LblForm');
            labelResponsables.innerText = 'Responsable ' + this.cantResponsables + ':';
            let divSelectResponsables= document.createElement("div");
            divSelectResponsables.setAttribute('class','col-md-10');
            let select = `<?php $Element = new Elements(); echo $Element->CBResponsables(); ?>`;
            divSelectResponsables.innerHTML = select;      
            divResponsables.appendChild(labelResponsables);
            divResponsables.appendChild(divSelectResponsables);
            divContenedor.appendChild(divResponsables);
        }

    }

    reset() {
        swal.fire({
            title: "¿Está seguro?",
            text: "¿Seguro de querer resetear el formulario?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((reset) => {
            if (reset) {
                this.reiniciarFormulario();
            }
        });
    }

    tomarElemento(xID) {
        return document.getElementById(xID);
    }

    crearElemento(xTipo) {
        return document.createElement(xTipo);
    }

    agregarAtributoxElemento(xElemento,xAtributo,xValue) {
        xElemento.setAttribute(xAtributo,xValue);
    }

    agregarEtiqueta(xElemento,xEtiqueta) {
        xElemento.innerHTML = xEtiqueta;
    }

    resetearValorElemento(xID) {
        document.getElementById(xID).value = "";
    }

    resetearValorSelect(xID) {
        document.getElementById(xID).selectedIndex = 0;
    }

    resetearValorDiv(xDiv) {
        xDiv.innerHTML = "";
    }

    agregarElementoxDiv(xDiv,xElemento) {
        xDiv.appendChild(xElemento);
    }

    reiniciarFormulario() {
        //RESETEANDO CAMPO FECHA
        this.resetearValorElemento("datepicker");        
        //RESETEANDO BOTON PERSONA
        let btnPersona = crearElemento("button");
        this.agregarAtributoxElemento(btnPersona,"type","button");
        this.agregarAtributoxElemento(btnPersona,"class","btn btn-lg btn-primary btn-block");
        this.agregarAtributoxElemento(btnPersona,"data-toggle","modal");
        this.agregarAtributoxElemento(btnPersona,"data-target","#ModalPersona");        
        agregarEtiqueta(btnPersona,"Seleccione una Persona");        
        let div_btnPersona = tomarElemento("Persona");
        resetearValorDiv(div_btnPersona);        
        agregarElementoxDiv(div_btnPersona,btnPersona);        
        //RESETEANDO BOTON SELECCIONE UN MOTIVO 1
        let btnMotivo_1 = crearElemento("button");
        this.agregarAtributoxElemento(btnMotivo_1,"type","button");
        this.agregarAtributoxElemento(btnMotivo_1,"class","btn btn-lg btn-primary btn-block");
        this.agregarAtributoxElemento(btnMotivo_1,"data-toggle","modal");
        this.agregarAtributoxElemento(btnMotivo_1,"data-target","#ModalMotivo_1");        
        this.agregarEtiqueta(btnMotivo_1,"Seleccione un Motivo");        
        let div_btnMotivo_1 = tomarElemento("Motivo_1");
        this.resetearValorDiv(div_btnMotivo_1);        
        this.agregarElementoxDiv(div_btnMotivo_1,btnMotivo_1); 
        //RESETEANDO BOTON SELECCIONE UN MOTIVO 2
        let btnMotivo_2 = crearElemento("button");
        this.agregarAtributoxElemento(btnMotivo_2,"type","button");
        this.agregarAtributoxElemento(btnMotivo_2,"class","btn btn-lg btn-primary btn-block");
        this.agregarAtributoxElemento(btnMotivo_2,"data-toggle","modal");
        this.agregarAtributoxElemento(btnMotivo_2,"data-target","#ModalMotivo_2");        
        this.agregarEtiqueta(btnMotivo_2,"Seleccione un Motivo");        
        let div_btnMotivo_2 = tomarElemento("Motivo_2");
        this.resetearValorDiv(div_btnMotivo_2);        
        this.agregarElementoxDiv(div_btnMotivo_2,btnMotivo_2);  
        //RESETEANDO BOTON SELECCIONE UN MOTIVO 3
        let btnMotivo_3 = crearElemento("button");
        this.agregarAtributoxElemento(btnMotivo_3,"type","button");
        this.agregarAtributoxElemento(btnMotivo_3,"class","btn btn-lg btn-primary btn-block");
        this.agregarAtributoxElemento(btnMotivo_3,"data-toggle","modal");
        this.agregarAtributoxElemento(btnMotivo_3,"data-target","#ModalMotivo_3");        
        this.agregarEtiqueta(btnMotivo_3,"Seleccione un Motivo");        
        let div_btnMotivo_3 = tomarElemento("Motivo_3");
        this.resetearValorDiv(div_btnMotivo_3);        
        this.agregarElementoxDiv(div_btnMotivo_3,btnMotivo_3);  
        //RESETEANDO OBSERVACIONES
        this.resetearValorElemento("Observaciones");
        //RESETEANDO RESPONSABLE
        this.resetearValorSelect("ID_Responsable");
        //RESETEANDO CENTRO DE SALUD
        this.resetearValorSelect("ID_Centro");
        //RESETEANDO RESPONSABLES CREADOS
        let divContenedor = tomarElemento("contenedorResponsables");
        this.resetearValorDiv(divContenedor);   
        this.cantResponsables = 1; 
    }
}
