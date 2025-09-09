import { Formulario } from "./Formulario.js";

let formulario = new Formulario(3, 1);

export function resetearForm() {
    formulario.reset();
 }

export function agregarMotivo() {
    formulario.addMotivo();
}

export function agregarResponsable() {
    formulario.addResponsable();
}