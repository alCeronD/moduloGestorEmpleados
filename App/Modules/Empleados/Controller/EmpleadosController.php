<?php 
class NoDataException extends Exception{}

require_once __DIR__ . '/../../../helpers/Regex.php';
require_once __DIR__ . '/../../../helpers/response.php';
require_once __DIR__ . '/../../../Config/Conn.php';
require_once __DIR__ . '/../Model/EmpleadosModel.php';

class EmpleadosController{

    // Clase completa Conn que representa la conexión
    private Conn $instanceConn;
    // Objeto mysqli que representa el resultado de getConnect.
    private mysqli $conn;
    private Response $response;
    
    private Regex $regex;

    private EmpleadosModel $empleadosModel;

    private array $dataObligatoria = [
        "nombre"=>'nombre Completo',
        "email"=> 'correo electronico',
        "sexo"=> "Genero",
        'area_id'=> "Área",
        'descripcion'=> "Descripción"
    ];

    public function __construct() {
        $this->regex = new Regex();
        $this->response = new Response();
        $this->instanceConn = new Conn();
        $this->instanceConn->setConect();
        $this->conn = $this->instanceConn->getConnect();
        $this->empleadosModel = new EmpleadosModel($this->conn);
    }

    /**
     * Summary of renderView - Renderizar vista 
     * @return void
     */
    public function renderView(){
        include_once __DIR__ . '/../View/EmpleadosView.php';
    }

    public function executeGetEmpleados(){
        $data = $this->empleadosModel->getEmpleados();
        $this->instanceConn->closeConnect();
        if (!$data['status']) {
            $this->response::success($data, 500);
        }
        $this->response::success($data,200);
    }

    /**
     * Summary of executeAddEmpleado - Funcion para ejecutar el insert del modelo y devolver respuesta.
     * @param array $data
     * @return void
     */
    public function executeAddEmpleado(array $dataEmpleado = []){
        try {
            if (count($dataEmpleado)== 0) {
                throw new NoDataException("Datos vacios.", 1);
            }

            // Validar que los datos obligatorios tengan información.



            // $dataResult = $this->empleadosModel->addEmpleado($dataEmpleado);

            // 201 = Recurso creado correctamente.
            // $this->response::success($dataResult, 201);

        } catch (NoDataException $th) {
            $result = [
                'status'=> false,
                'message'=> $th->getMessage()
            ];
            // Codigo 422 = Los datos no cumplen con las reglas esperadas para continuar con el proceso.
           $this->response::success($result, 422);
        }
    }
}
$obj = new EmpleadosController();
// Recibe la data desde la petición javascript.
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = file_get_contents('http/input');
        $data = json_decode($json, true);
        switch ($data['action']) {
            case 'add':
                # code...
                break;
            
            default:
                # code...
                break;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $case = $_GET['action'] ?? '';

        switch ($case) {
            case 'getEmpleados':
                if (method_exists($obj, 'executeGetEmpleados')) {
                    $obj->executeGetEmpleados();
                }

                break;
            case 'label':
                # code...
                break;
            default:
                # code...
                break;
        }
    }

    exit();
}



?>