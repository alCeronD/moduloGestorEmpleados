<?php 

use Dba\Connection;

require_once __DIR__ . '/../../../Config/Conn.php';

class EmpleadosModel{
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function getEmpleados() {
    try {
        // ✅ Primera consulta SIN roles
        $sql = "SELECT 
                    em.idEmpleado AS idEmpleado,
                    em.nombre AS nombreEmpleado,
                    em.email AS email,
                    em.sexo AS genero,
                    em.boletin AS boletin,
                    em.descripcion AS descripcion,
                    ar.nombre AS nombreArea,
                    ar.idArea AS idArea
                FROM empleados em
                LEFT JOIN area ar ON em.area_id = ar.idArea";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $empleados = [];

        while ($row = $result->fetch_assoc()) {
            $empleados[] = $row;
        }

        $result->free();
        $stmt->close();

        // ✅ Segunda consulta para obtener roles por cada empleado
        $sql2 = "SELECT 
                    r.idRol AS idRol,
                    r.nombre AS nombreRol
                FROM roles r
                INNER JOIN empleado_rol emr ON emr.rol_id = r.idRol
                WHERE emr.empleadoId = ?";

        $stmt2 = $this->conn->prepare($sql2);

        if (!$stmt2) {
            throw new Exception("Error al preparar consulta de roles: " . $this->conn->error);
        }

        foreach ($empleados as $key => $empleado) {
            $idEmpleado = $empleado['idEmpleado'];
            $stmt2->bind_param("i", $idEmpleado);
            $stmt2->execute();
            $resRoles = $stmt2->get_result();

            $roles = [];
            while ($rol = $resRoles->fetch_assoc()) {
                $roles[] = $rol;
            }

            $resRoles->free();
            $empleados[$key]['rolesAsociados'] = $roles;
        }

        $stmt2->close();

        return [
            'data' => $empleados,
            'message' => count($empleados) == 0 ? "No hay empleados registrados" : "Empleados registrados",
            'status' => true
        ];

    } catch (Exception $th) {
        return [
            'status' => false,
            'message' => $th->getMessage()
        ];
    }
    }

    public function getAreas()
    {
        try {
            $sql = "SELECT 
                    ar.idArea AS idArea,
                    ar.nombre AS nombreArea
                FROM area ar";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $this->conn->error);
            }

            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            $areas = [];
            $dataResult = $stmt->get_result();
            while ($row = $dataResult->fetch_assoc()) {
                $areas[] = $row;
            }

            return [
                'data' => count($areas) === 0 ? [] : $areas,
                'message' => count($areas) === 0 ? "No hay áreas registradas" : "Áreas registradas",
                'status' => true
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getRoles()
{
    try {
        $sql = "SELECT 
                    r.idRol AS idRol,
                    r.nombre AS nombreRol
                FROM roles r";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $roles = [];
        $dataResult = $stmt->get_result();
        while ($row = $dataResult->fetch_assoc()) {
            $roles[] = $row;
        }

        return [
            'data' => count($roles) === 0 ? [] : $roles,
            'message' => count($roles) === 0 ? "No hay roles registrados" : "Roles registrados",
            'status' => true
        ];
    } catch (Exception $e) {
        return [
            'status' => false,
            'message' => $e->getMessage()
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

    /**
     * Función para actualizar datos del usuario
     * @param array $empleado
     * @return array{message: string, status: bool}
     */
    public function updateEmpleado(array $empleado = []){

        try {
            $this->conn->begin_transaction();
            // 1 transacción - Extraigo los roles asociados al usuario en base al id del empleado
            $sql = "SELECT empleadoId AS idEmpleado, rol_id AS rolAsociado FROM empleado_rol WHERE empleadoId = ?";
            $stmt1 = $this->conn->prepare($sql);
            if(!$stmt1) return [ 'status'=> false, 'message'=> "error al preparar la consulta".$this->conn->error];
            $stmt1->bind_param('i', $empleado['idEmpleado']);
            if(!$stmt1->execute()){
                $this->conn->rollback();
                return ['status'=> false, 'message'=> "error al ejecutar la consulta".$stmt1->error];
            }
            $roles = [];
            $result = $stmt1->get_result();

            while($row = $result->fetch_assoc()){
                $roles []= $row;
            }
            $result->free();
            $stmt1->close();

            //2 Transaccion - ELIMINAR LOS ROLES DE LA BASE DE DATOS E INSERTAR LOS NUEVOS.
            $sql2 = "DELETE FROM empleado_rol WHERE empleadoId = ?";
            $stmt2 = $this->conn->prepare($sql2);
            if(!$stmt2) return [ 'status'=> false, 'message'=> "error al preparar la consulta".$this->conn->error];

            $stmt2->bind_param('i', $empleado['idEmpleado']);
            
            if(!$stmt2->execute()){
                $this->conn->rollback();
                throw new Exception("Error al ejecutar la consulta de transacción".$stmt2->error);
            } 

            $stmt2->close();

            // 3 Transaccion - Insertar los nuevos roles asociados;
            $sql3 = "INSERT INTO empleado_rol (empleadoId, rol_id) VALUES (?,?)";
            $stmt3 = $this->conn->prepare($sql3);
            if(!$stmt3) return [ 'status'=> false, 'message'=> "error al preparar la consulta".$this->conn->error];

            $rolesUsuario = $empleado["rolesAgregados"];
            foreach ($rolesUsuario as $value) {

                $stmt3->bind_param('ii', $empleado['idEmpleado'], $value);
                if(!$stmt3->execute()){
                    $this->conn->rollback();
                    return [
                        'status'=>false,
                        'message'=> "Error al ejecutar el procedimiento".$stmt3->error
                    ];
                }
            }

            // 4 Transacción - Actualizar la información enviada por el usuario.

            $sql4 = "UPDATE empleados SET nombre = ?, email = ?, sexo = ?, area_id =?, boletin = ?, descripcion = ? WHERE idEmpleado = ?";

            $stmt4 = $this->conn->prepare($sql4);
            if(!$stmt4) return [ 'status'=> false, 'message'=> "error al preparar la consulta".$this->conn->error];
            
            $stmt4->bind_param('sssiisi', $empleado['nombre'], $empleado['email'], $empleado['genero'], $empleado['area_id'], $empleado['boletin'],$empleado['descripcion'], $empleado['idEmpleado']);

            if (!$stmt4->execute()) {
                $this->conn->rollback();
                return [
                    'status'=> false,
                    'message'=> 'error al ejecutar el proceso'.$stmt4->error
                ];
            }
            
            $this->conn->commit();

            return [
                'message' => 'Datos del empleado actualizados',
                'status'=> true
            ];
        } catch (\Throwable $e) {
            $this->conn->rollback();
            return [
                'status'=>false,
                'message'=> $e->getMessage()
            ];
        }
    }
}



?>