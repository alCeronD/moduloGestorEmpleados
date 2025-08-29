<?php 

class Regex{
        /**
     * Summary of SOLO_NUMEROS
     * @var string Expresión regular para validar que el valor recibido solamente tiene números.
     */
    public const SOLO_NUMEROS = '/^\d+$/';

    /**
     * Summary of SOLO_LETRAS
     * @var string Expresión regular para validar que el valor recibido solo tenga letras y no contenga ni carácteres especiales
     */
    public const SOLO_LETRAS = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/';

    public const CORREO = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';

    public static function validarNumeros($value){
        return (bool) preg_match(self::SOLO_NUMEROS, $value);
    }

    public static function validarLetras($value){
        return (bool) preg_match(self::SOLO_LETRAS, $value);
    }
    
    public static function validarEmail($value){
        if(!str_contains($value, '@')) return false;
        return (bool) preg_match(self::CORREO, $value);
    }

    public static function validarEmailNative($value){
        // if(empty($value)) return (bool) true;
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}

?>