<?php
require_once __DIR__ . '/App/Config/Conn.php';
require_once __DIR__ . '/App/Modules/Empleados/Controller/EmpleadosController.php';

$conn = new Conn();
$conn->setConect();
$mysqli = $conn->getConnect();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba tecnica.</title>
    <link rel="stylesheet" href="App/Assets/css/main.css">
</head>

<body>

    <div class="container">
        <?php
        $empleado = new EmpleadosController();
        $empleado->renderView();

        ?>
    </div>

    <script type="module" src="App/Assets/JS/empleados.js"></script>

</body>

</html>