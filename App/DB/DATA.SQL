
-- Registros de prueba previos.

INSERT INTO roles (nombre) VALUES ("Ventas"),("Administración"), ("Calidad"), ("Producción");

INSERT INTO area (nombre) VALUES 
("Profesional de proyectos - Desarrollador"),
("Gerente estratégico"),
("Auxiliar administrativo");

INSERT INTO empleado_rol(empleadoId, rol_id) VALUES (1,1), (2,3);

INSERT INTO empleados (nombre, email, sexo, area_id, boletin, descripcion) VALUES
('Carlos Ramírez', 'carlos.ramirez@example.com', 'M', 1, 1, 'Desarrollador con experiencia en aplicaciones web y móviles.'),
('María López', 'maria.lopez@example.com', 'F', 2, 0, 'Gerente con enfoque en estrategias de crecimiento organizacional.'),
('Juan Pérez', 'juan.perez@example.com', 'M', 3, 1, 'Auxiliar administrativo con excelentes habilidades en gestión documental.'),
('Ana Torres', 'ana.torres@example.com', 'F', 1, 0, 'Desarrolladora apasionada por la innovación tecnológica.');
