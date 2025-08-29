

/**
 * Description Funcion para validar que elementos son obligatorios deben tener incoformacion y cuales no.
 *
 * @param {*} dataForm - objeto capturada del formData
 * @param {*} opcionales - campos opcionales por el usuario
 * @param {*} inputsFormEdit - mapeo general de los inputs del formulario para mostrar mensaje de alerta al usuario
 * @returns {boolean} 
 */
export const validateData = (dataForm, opcionales,inputsFormEdit )=>{
    for (const [key, value] of dataForm.entries()) {
        console.log(key, value);
    const isEmpty = !value || value.toString().trim() === "";
    // pasamos por referencia los valores opcionales.
    const camposOpcionales = opcionales;
    if (isEmpty && !camposOpcionales.includes(key)) {
        console.log(inputsFormEdit[key]);
      alert(`El campo ${inputsFormEdit[key]} no debe estar vacio`);
      return false;
    }
  }
  return true;

}