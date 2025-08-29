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

    public function executeGetAreas(){
        $dataAreas = $this->empleadosModel->getAreas();
        $this->instanceConn->closeConnect();
        if (!$dataAreas['status']) {
            $this->response::success($dataAreas, 500);
            
        }
        $this->response::success($dataAreas,200);
    }

    public function executeGetRoles(){
        $dataRoles = $this->empleadosModel->getRoles();
        $this->instanceConn->closeConnect();
        if (!$dataRoles['status']) {
            $this->response::success($dataRoles, 500);
                    
            }
        $this->response::success($dataRoles,200);
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

    /**
     * Función para validar campos recibidos desde el front y actualizar los recursos.
     * @param array $data
     * @throws \Exception
     * @throws \NoDataException
     * @return void
     */
    public function executeUpdateEmpleado(array $data =[]){
        try {
            
            if (count($data) === 0) throw new NoDataException("No hay datos para continuar con el proceso", 1);  
            $roles = $data['rolesAgregados'];

            if (count($roles) === 0) throw new NoDataException("El rol del empleado debe ser obligatorio", 1);
            if(!$this->regex::validarEmailNative($data['email'])) throw new Exception("Correo no válido");
            if(!$this->regex::validarLetras($data['nombre'])) throw new Exception("Nombre digitado no valido");
            if(empty($data['idEmpleado'])) throw new NoDataException("No se encuentra el id del empleado");
            if(empty($data['descripcion'])) throw new NoDataException("La descripción es obligatoria");
            if(empty($data['area_id'])) throw new NoDataException("El area del empleado es obligatoria");
            if(empty($data['genero'])) throw new NoDataException("El genero del empleado es obligatorio");
            

            $data['boletin'] = empty($data['boletin']) ? 0 : 1;
            $data['area_id'] = (int) $data['area_id'];
            // Vacio el arreglo para después agregarlos con el formato de int.
            $data['rolesAgregados'] =[];
            $data['idEmpleado'] = (int) $data['idEmpleado'];
            foreach ($roles AS $rl){
                $data["rolesAgregados"][]= (int) $rl;
            }
            $updateResult = $this->empleadosModel->updateEmpleado($data);
            $this->instanceConn->closeConnect();

            if (!$updateResult['status']) {
                $this->response::success($updateResult, 500);
            }

            $this->response::success($updateResult, 200);

        } catch (Exception $th) {
            $result = [
                'status'=>false,
                'message'=> $th->getMessage()
            ];
            $this->response::success($result, 422);
        } catch( NoDataException $f){
            $result = [
                'status'=> false,
                'message'=> $f->getMessage()
            ];
            $this->response::success($result, 422);

        }
    }
}
$obj = new EmpleadosController();
// Recibe la data desde la petición javascript.
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        switch ($data['action']) {
            case 'add':
                # code...
                break;

                case 'updateEmpleado':
                    unset($data['action']);
                    if (method_exists($obj, 'executeUpdateEmpleado')) {
                        $obj->executeUpdateEmpleado($data);
                    }
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
            case 'getAreas':
                 if (method_exists($obj, 'executeGetAreas')) {
                    $obj->executeGetAreas();
                }
                break;

            case 'getRoles':
                if (method_exists($obj,'executeGetRoles')) {
                    $obj->executeGetRoles();
                }
                break;

            default:
                # code...
                break;
        }
    }

    exit();
}

?>