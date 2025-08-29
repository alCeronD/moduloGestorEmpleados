# moduloGestorEmpleados

---

Proceso de prueba nexus password

---

## Requerimientos

- Servidor Apache
- PHP 8.0 >=
- Navegador Web
- Gestor base de datos

---

## Estructura de carpetas del proyecto.

Directory structure:
└── alcerond-modulogestorempleados/
├── README.md
├── index.php - Archivo inicial
└── App/
├── Assets/
│ ├── css/
│ │ └── main.css - Css general del modulo empleados
│ └── js/
│ ├── empleados.js - Archivo que contiene información logica de negocio
│ └── cases/
│ ├── fetch.js - Funciones para hacer peticiones post y get
│ ├── regex.js - Funciones para validar expresiones regulares
│ └── utils.js - Funciones logica de negocio para validaciones
├── Config/
│ ├── Config.php - constantes para conectar a la base de datos
│ └── Conn.php - Clase para conectar a la base de datos
├── DB/
│ ├── DATA.SQL - Consultas insert de prueba
│ └── SQUEMA.sql - Consultas de creación de tablas
│ └── empleados.sql - Base de datos con información cargada.
├── helpers/
│ ├── Exception.php
│ ├── Regex.php - Clase con funciones estaticas para validar datos
│ └── Response.php - Función para devolver respuesta al cliente
└── Modules/
└── Empleados/
├── Controller/
│ └── EmpleadosController.php - Flujo central del modulo
├── Model/
│ └── EmpleadosModel.php - Consultas
└── View/
└── EmpleadosView.php - Vista del modulo empleados

## Requisitos de instalación

---

1-Apache y gestor **mysql** (preferiblemente xampp o wamp)



## Proceso de instalación

1. Coloca la carpeta dentro del proyecto raiz de tu servidor local
``
### Xampp
C:\xampp\htdocs\

### Wampp 
C:\wamp64\www\
``

2. Crea la base de datos

Abre tu navegador y entra a la siguiente url " http://localhost/phpmyadmin

3. Crea una nueva base de datos con el nombre que corresponda, en este caso, empleados

4. Asegurarse de que el nombre de la base de datos sea la correcta, el nombre la encuentras en la ruta App/Config/Config.php

``` PHP

define("DB_NAME","empleados"); # Nombre de la base de datos.
define("DB_PASSWORD", "");
define("DB_USER","root");
define("DB_HOST", "localhost");

```

5. Dirijase a la aplicación xampp y en su ruta de instalación ejecute la aplicación xampp-control.exe

6. pulse en los botones **start** de la sección apache y Mysql

7. Abre tu navegador e ingresa a http://localhost/

8. Seleccione la carpeta "moduloGestorEmpleados"

