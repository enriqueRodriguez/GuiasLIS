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
('Productos');

-- Tabla Imagenes
CREATE TABLE Imagenes (
    IdImagen INT AUTO_INCREMENT PRIMARY KEY,
    Ruta VARCHAR(255) NOT NULL,
    IdTipoImagen INT NOT NULL,
    FOREIGN KEY (IdTipoImagen) REFERENCES TipoImagen(IdTipoImagen)
);

-- Agregar valores por defecto a la tabla Imagenes
INSERT INTO Imagenes (Ruta, IdTipoImagen) VALUES
('images/carrousel/textile1.jpg', 1),
('images/carrousel/promo1.jpg', 1),
('images/carrousel/textile2.jpg', 1),
('images/carrousel/textile3.jpg', 1);

-- Tabla Usuarios
CREATE TABLE Usuarios (
    IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Nombre VARCHAR(100) NOT NULL,
    Apellido VARCHAR(100) NOT NULL,
    TipoUsuario INT NOT NULL,
    IdImagen INT,
    FOREIGN KEY (TipoUsuario) REFERENCES TipoUsuario(IdTipoUsuario),
    FOREIGN KEY (IdImagen) REFERENCES Imagenes(IdImagen)
);

-- Agregar valores por defecto a la tabla Usuarios
INSERT INTO Usuarios (Username, Password, Nombre, Apellido, TipoUsuario, IdImagen) VALUES
('admin', '1234', 'admin', 'adminA', 1, NULL),      -- Administrador
('empleado', '1234', 'empleado', 'empleadoA', 2, NULL), -- Empleado
('cliente', '1234', 'cliente', 'clienteA', 3, NULL); -- Cliente

-- Tabla Categorias
CREATE TABLE Categorias (
    IdCategoria INT AUTO_INCREMENT PRIMARY KEY,
    Descripcion VARCHAR(100) NOT NULL
);

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

-- Tabla Ventas
CREATE TABLE Ventas (
    IdVenta INT AUTO_INCREMENT PRIMARY KEY,
    IdUsuario INT NOT NULL,
    Total DECIMAL(10, 2) NOT NULL CHECK (Total >= 0),
    FechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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