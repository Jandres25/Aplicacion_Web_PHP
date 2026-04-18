-- Datos iniciales para el Sistema de Gestión Empresarial

-- Seeders para tbl-puestos
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(1, 'Programador Semi Sr.');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(2, 'Programador Sr.');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(3, 'Líder de Proyectos');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(4, 'Desarrolador de base de datos');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(5, 'Programador Fullstack');
INSERT INTO `tbl-puestos` (`ID`, `Nombredelpuesto`) VALUES(6, 'Diseñador Web');

-- Seeders para tbl-usuarios
INSERT INTO `tbl-usuarios` (`ID`, `Nombreusuario`, `Password`, `Correo`) VALUES(1, 'Administrador', '1234', 'admin@gmail.com');
INSERT INTO `tbl-usuarios` (`ID`, `Nombreusuario`, `Password`, `Correo`) VALUES(2, 'Kevin 11', '7896', 'Juanito@gmail.com');
INSERT INTO `tbl-usuarios` (`ID`, `Nombreusuario`, `Password`, `Correo`) VALUES(3, 'Hacker', 'Hackerman15', 'Hackandslash@hotmail.com');

-- Seeders para tbl-empleados (Opcional, datos de ejemplo)
INSERT INTO `tbl-empleados` (`ID`, `Primernombre`, `Segundonombre`, `Primerapellido`, `Segundoapellido`, `Idpuesto`, `Fecha`) VALUES(1, 'Charles', '', 'W.', 'Bachman', 2, '2020-04-20');
INSERT INTO `tbl-empleados` (`ID`, `Primernombre`, `Segundonombre`, `Primerapellido`, `Segundoapellido`, `Idpuesto`, `Fecha`) VALUES(2, 'Eduard', 'Frank', 'Codd', 'Smith', 6, '2017-10-01');
