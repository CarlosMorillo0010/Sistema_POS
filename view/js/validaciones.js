/*
 *  VALIDACIONES DE INICIO DE SESIÓN
 * */
const formulario = document.getElementById('formulario__login');
const inputs = document.querySelectorAll('#formulario__login input');

 const expresiones ={
     user: /^[0-9]{8,8}$/,
     password: /^.{5,8}$/
 }

 const campos = {
     usuario: false,
     password: false
 }

 const validarFormulario = (e) => {
     switch (e.target.name){
         case "usuario":
                validarCampo(expresiones.user, e.target, 'usuario');
             break;
         case "password":
                validarCampo(expresiones.password, e.target, 'password');
             break;
     }
 }

 const validarCampo = (expresion, input, campo) => {
     if (expresion.test(input.value)){
         document.getElementById(`grupo__${campo}`).classList.remove('formulario__grupo-incorrecto');
         document.getElementById(`grupo__${campo}`).classList.add('formulario__grupo-correcto');
         document.querySelector(`#grupo__${campo} i`).classList.add('fa-check-circle');
         document.querySelector(`#grupo__${campo} i`).classList.remove('fa-times-circle');
         document.querySelector(`#grupo__${campo} .formulario__input-error`).classList.remove('formulario__input-error-activo');
         campos[campo] = true;
     }else{
         document.getElementById(`grupo__${campo}`).classList.add('formulario__grupo-incorrecto');
         document.getElementById(`grupo__${campo}`).classList.remove('formulario__grupo-correcto');
         document.querySelector(`#grupo__${campo} i`).classList.add('fa-times-circle');
         document.querySelector(`#grupo__${campo} i`).classList.remove('fa-check-circle');
         document.querySelector(`#grupo__${campo} .formulario__input-error`).classList.add('formulario__input-error-activo');
         campos[campo] = false;
     }
 }

 inputs.forEach((input) => {
     input.addEventListener('keyup', validarFormulario);
     input.addEventListener('blur', validarFormulario);
 });

$("#btnValidar").click(function(event) {
    if(!$("#divRadios input[name='estatusPagos']").is(':checked')){
        alert('Favor de seleccionar una opción');
    }
});