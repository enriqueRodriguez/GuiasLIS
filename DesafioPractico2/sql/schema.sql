CREATE DATABASE IF NOT EXISTS TextilExport;
USE TextilExport;

-- Tabla TipoUsuario
CREATE TABLE TipoUsuario (
    IdTipoUsuario INT AUTO_INCREMENT PRIMARY KEY,
    Descripcion VARCHAR(100) NOT NULL
);

-- Agregar valores por defecto a la tabla TipoUsuario
INSERT INTO TipoUsuario (Descripcion) VALUES 
('Administrador'),
('Empleado'),
('Cliente');

-- Tabla TipoImagen
CREATE TABLE TipoImagen (
    IdTipoImagen INT AUTO_INCREMENT PRIMARY KEY,
    Descripcion VARCHAR(100) NOT NULL
);

-- Agregar valores por defecto a la tabla TipoImagen
INSERT INTO TipoImagen (Descripcion) VALUES 
('Carrusel'),
('Productos'),
('Usuarios');

-- Tabla Imagenes
CREATE TABLE Imagenes (
    IdImagen INT AUTO_INCREMENT PRIMARY KEY,
    Ruta VARCHAR(255) NOT NULL,
    IdTipoImagen INT NOT NULL,
    FOREIGN KEY (IdTipoImagen) REFERENCES TipoImagen(IdTipoImagen)
);

-- Agregar valores por defecto a la tabla Imagenes
INSERT INTO Imagenes (Ruta, IdTipoImagen) VALUES
('/images/carrousel/textile1.jpg', 1),
('/images/carrousel/promo1.jpg', 1),
('/images/carrousel/textile2.jpg', 1),
('/images/carrousel/textile3.jpg', 1),
('/images/products/PROD00002_1745802639.png', 2),
('/images/products/PROD00003_1740375633.jpg', 2),
('/images/products/PROD00004_1740375690.jpg', 2),
('/images/products/PROD00005_1740375847.jpg', 2),
('/images/products/PROD00006_1740375947.jpg', 2),
('/images/products/PROD00007_1740376023.jpg', 2),
('/images/products/PROD00008_1740376107.jpg', 2),
('/images/products/PROD00009_1740376182.jpg', 2),
('/images/products/PROD00010_1740376304.jpg', 2),
('/images/products/PROD00011_1740376472.jpg', 2),
('/images/products/PROD00012_1740376541.jpg', 2),
('/images/products/PROD00013_1740376617.jpg', 2),
('/images/products/PROD00014_1740376813.jpg', 2);

-- Tabla Usuarios
CREATE TABLE Usuarios (
    IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Nombre VARCHAR(100) NOT NULL,
    Apellido VARCHAR(100) NOT NULL,
    TipoUsuario INT NOT NULL,
    IdImagen INT,
    Activo TINYINT(1) NOT NULL DEFAULT 1, -- Nuevo campo
    FOREIGN KEY (TipoUsuario) REFERENCES TipoUsuario(IdTipoUsuario),
    FOREIGN KEY (IdImagen) REFERENCES Imagenes(IdImagen)
);

-- Agregar valores por defecto a la tabla Usuarios
INSERT INTO Usuarios (Username, Password, Nombre, Apellido, TipoUsuario, IdImagen, Activo) VALUES
('admin', '$2y$12$MJ26yi548Iayx80kUQsMe.yTiZxgCK4sxGsxbkA.hxaaz5kGjNMhu', 'admin', 'adminA', 1, NULL, 1),      -- Administrador
('empleado', '$2y$12$BI7d5pLwhNcavSQQ3w2FBeamUTFN9AMYuJ4.DIrL0TfrjEJ6OA.qC', 'empleado', 'empleadoA', 2, NULL, 1), -- Empleado
('cliente', '$2y$12$noa22hAlndyopWX1zIAr/.J1.jfExENTr0OlnWkqrQTjCsvXMwsS.', 'cliente', 'clienteA', 3, NULL, 1); -- Cliente

-- Tabla Categorias
CREATE TABLE Categorias (
    IdCategoria INT AUTO_INCREMENT PRIMARY KEY,
    Descripcion VARCHAR(100) NOT NULL
);

-- Agregar valores por defecto a la tabla Categorias
INSERT INTO Categorias (Descripcion) VALUES ('Textil'), ('Promocional');

-- Tabla Productos
CREATE TABLE Productos (
    IdProducto VARCHAR(10) PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Cantidad INT NOT NULL CHECK (Cantidad >= 0),
    Precio DECIMAL(10, 2) NOT NULL CHECK (Precio >= 0),
    IdCategoria INT NOT NULL,
    IdImagen INT,
    FechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FechaActualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (IdCategoria) REFERENCES Categorias(IdCategoria),
    FOREIGN KEY (IdImagen) REFERENCES Imagenes(IdImagen)
);

-- Agregar valores por defecto a la tabla Productos
INSERT INTO Productos (IdProducto, Nombre, Cantidad, Precio, IdCategoria, IdImagen) VALUES
('PROD00002', 'Taza Térmica', 50, 12.50, 2, 5),
('PROD00003', 'Camiseta de Algodón Unisex', 4, 12.99, 1, 6),
('PROD00004', 'Sudadera con Capucha Premium', 2, 34.99, 1, 7),
('PROD00005', 'Pantalón Deportivo de Poliéster', 5, 22.50, 1, 8),
('PROD00006', 'Bufanda de Lana Hecha a Mano', 0, 18.75, 1, 9),
('PROD00007', 'Blusa de Lino para Mujer', 3, 28.90, 1, 10),
('PROD00008', 'Shorts de Mezclilla Clásicos', 0, 19.99, 1, 11),
('PROD00009', 'Gorro de Invierno de Punto', 6, 14.50, 1, 12),
('PROD00010', 'Pijama de Algodón', 1, 29.99, 1, 13),
('PROD00011', 'Bolsas Ecológicas Reutilizables', 3, 4.99, 2, 14),
('PROD00012', 'Toallas Deportivas con Logo', 0, 6.75, 2, 15),
('PROD00013', 'Llaveros de Tela Personalizados', 4, 3.50, 2, 16),
('PROD00014', 'Delantales Promocionales', 5, 10.50, 2, 17);

-- Tabla Ventas
CREATE TABLE Ventas (
    IdVenta INT AUTO_INCREMENT PRIMARY KEY,
    IdUsuario INT NOT NULL,
    Total DECIMAL(10, 2) NOT NULL CHECK (Total >= 0),
    FechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    RutaComprobante VARCHAR(255) NULL,
    FOREIGN KEY (IdUsuario) REFERENCES Usuarios(IdUsuario)
);

-- Tabla VentasProductos
CREATE TABLE VentasProductos (
    IdVentaProducto INT AUTO_INCREMENT PRIMARY KEY,
    IdVenta INT NOT NULL,
    IdProducto VARCHAR(10) NOT NULL,
    Cantidad INT NOT NULL CHECK (Cantidad >= 0),
    Precio DECIMAL(10, 2) NOT NULL CHECK (Precio >= 0),
    Total DECIMAL(10, 2) NOT NULL CHECK (Total >= 0),
    FOREIGN KEY (IdVenta) REFERENCES Ventas(IdVenta),
    FOREIGN KEY (IdProducto) REFERENCES Productos(IdProducto)
);