<?php 

Class Conn{

    private mysqli $conn;
    
    public function __construct() {
    }

    public function setConect(){
        try {
            require_once __DIR__ . '/Config.php';
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            if ($conn->connect_error) {
                throw new Exception("Error de conexión", 1);
            }

            $this->conn = $conn;
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function getConnect(){
        return $this->conn ?? null;
    }

    /**
     * Cerrar la conexión.
     * @return void
     */
    public function closeConnect(){
        if (isset($this->conn)) {
            $this->conn->close();
        }
    }

}

?>