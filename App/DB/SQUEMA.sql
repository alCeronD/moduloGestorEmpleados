-- Esquema creación de tablas.

CREATE TABLE IF NOT EXISTS empleados(
    idEmpleado INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT COMMENT "Identificador del empleado",
    nombre VARCHAR(255) NOT NULL COMMENT "Nombre completo del empleado, obligatorio",
    email VARCHAR(255) NOT NULL COMMENT "Email empleado, obligatorio",
    sexo CHAR(1) NOT NULL COMMENT "Campo de genero donde M(Masculino) y F(Femenino) obligatorio",
    area_id INT (11) COMMENT "Area en donde pertenece el empleado",
    boletin INT (11) NOT NULL COMMENT "Id referenciado para recibir boletin donde 1 es recibir boletín, 0 para no recibir boletín",
    descripcion TEXT NOT NULL COMMENT "Campo de descripción del empleado, obligatorio",
    CONSTRAINT FK_area_id FOREIGN KEY (area_id) REFERENCES area(idArea) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS empleado_rol(
    idEmpleadoRol INT(11) PRIMARY KEY AUTO_INCREMENT COMMENT "Id primario AUTO_INCREMENT de la tabla muchos a muchos entre empleado y rol",
    empleadoId INT(11) COMMENT "Identificador llave foraneo del empleado",
    rol_id INT(11) COMMENT "Identificador llave foranea del rol",
    CONSTRAINT FK_empleadoID FOREIGN KEY (empleadoId) REFERENCES empleados(idEmpleado) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT FK_rolID FOREIGN KEY (rol_id) REFERENCES roles(idRol) ON DELETE SET NULL ON UPDATE CASCADE  
);

CREATE TABLE IF NOT EXISTS roles(
idRol INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT COMMENT "Identificador primario del rol",
nombre VARCHAR(255) NOT NULL COMMENT "Nombre del rol"
);

CREATE TABLE IF NOT EXISTS area(
    idArea INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT COMMENT "Identificador primario del area",
    nombre VARCHAR(255) NOT NULL COMMENT "Nombre del área de la empresa"
);