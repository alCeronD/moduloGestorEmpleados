<?php 


class EmpleadosModel{
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function getEmpleados(){
        try {

            $sql = "SELECT 
                em.nombre AS 'nombreEmpleado',
                em.email AS 'email',
                em.sexo AS 'genero',
                em.boletin AS 'boletin',
                ar.nombre AS 'nombreArea',
                ar.idArea AS 'idArea'
                FROM empleados em
                LEFT JOIN area ar ON em.area_id = ar.idArea";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error al capturar data: " . $this->conn->error);
            }

            if(!$stmt->execute()){
                throw new Exception("Error al ejecutar la consulta". $this->conn->error);
                
            }

            $empleados = [];
            $dataResult = $stmt->get_result();
            while ($row = $dataResult->fetch_assoc()){
                $empleados[] = $row;
            }

            return [
                'data'=> count($empleados) == 0 ? [] : $empleados,
                'message'=> count($empleados) == 0 ? "No hay empleados registrados" : "Empleados registrados",
                'status'=> true
            ];


        } catch (Exception $th) {
            return [
                'status'=> false,
                'message'=> $th->getMessage()
            ];
        }

    }

    public function addEmpleado(array $empleado = []){
        try {
            if (count($empleado) === 0) {
                throw new Exception("Datos del empleado no recibidos no recibidos correctamente.");
            }

            extract($empleado,EXTR_PREFIX_SAME, 'eml_' );

            $sql = "INSERT INTO empleados (`nombre`, `email`, `sexo`, `area_id`, `boletin`, `descripcion`) VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error al preparar la consulta".$this->conn->error);
            }

            $stmt->bind_param('sssiis',$eml_nombre, $eml_email, $eml_sexo, $eml_areaId, $eml_boletin, $eml_descripcion );

            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar proceso".$stmt->error);
            }

            return [
                'status'=> true,
                'message' => "empleado agregado correctamente"
            ];




        } catch (Exception $e) {
            return [
                'status'=>false,
                'message'=> $e->getMessage()
            ];
        }
    }
}


?>