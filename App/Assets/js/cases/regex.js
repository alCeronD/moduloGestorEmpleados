
// Reglas
const validationRules = {
  soloLetras: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/,
  correo: /^[^\s@]+@[^\s@]+\.[^\s@]+$/

};

export const validateNombre = (input)=>{
    return validationRules.soloLetras.test(input);
}

export const validateEmail = (input)=>{
    return validationRules.correo.test(input);
}