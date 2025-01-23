import swal from 'sweetalert2';

export function controlMovimiento(object) {
    if (object.value == "1") {
        swal.fire({
        title: "",
        html: "Al seleccionar la opción 'Todos' se mostrarán las personas con y sin movimiento",
        icon: "warning",
        customClass: {
            htmlContainer: "text-dialog"
        },
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: `OK`,
        cancelButtonText: `Cancel`
        }).then((selectOption) => {
            if (selectOption.isConfirmed) {
                object.value = "1";
            } else {
                object.value = "0";
            }
        });
    };
}

export function Verificar(xID){
    swal.fire({
      title: "¿Está seguro?",
      text: "¿Seguro de querer eliminar este movimiento?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: `OK`,
      cancelButtonText: `Cancel`,
    })
    .then((selectOption) => {
        if (selectOption.isConfirmed) {
        window.location.href = 'Controladores/DeleteMovimiento.php?ID='+xID;
      }
    });
  }

export function mensajeDeProcesamiento(mensaje){
    swal.fire(mensaje, '', 'success');
}