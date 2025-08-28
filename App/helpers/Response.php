<?php 

class Response{

    // Función de success
    public static function success(array $result = [], $code = null){

        http_response_code($code);
        echo json_encode($result,JSON_PRETTY_PRINT);
        exit();
    }

}

?>