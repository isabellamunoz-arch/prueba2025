-- Create on 20250909
-- Database tiendaonline - ACTUALIZADA con carrito y pedidos

CREATE DATABASE IF NOT EXISTS tiendaonline DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE tiendaonline;

-- --------------------
-- PARAMETRIC TABLES --
-- --------------------

-- 01 Table roles
CREATE TABLE IF NOT EXISTS roles (
  id_rol tinyint NOT NULL AUTO_INCREMENT,
  nombre_rol varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(id_rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 02 Table marcas
CREATE TABLE IF NOT EXISTS marcas (
  id_marca tinyint NOT NULL AUTO_INCREMENT,
  nombre_marca varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(id_marca)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- ---------------------------
-- TABLES WITH FOREIGN KEYS --
-- ---------------------------

-- 03 Table usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id_usuario varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  nombre_usuario varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  email_usuario varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  direccion_usuario varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  telefono_usuario varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  clave_usuario varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  id_rol_usuario tinyint NOT NULL,
  PRIMARY KEY(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE usuarios
  ADD KEY(id_rol_usuario),
  ADD CONSTRAINT fk_rol_usuario FOREIGN KEY (id_rol_usuario)
      REFERENCES roles (id_rol)
      ON UPDATE NO ACTION
      ON DELETE NO ACTION;

-- 04 Table productos
CREATE TABLE IF NOT EXISTS productos (
  id_producto int NOT NULL AUTO_INCREMENT,
  nombre_producto varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  descripcion_producto varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  valor_producto int NOT NULL,
  cantidad_producto int NOT NULL,
  id_marca_producto tinyint NOT NULL,
  PRIMARY KEY(id_producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

ALTER TABLE productos
  ADD KEY(id_marca_producto),
  ADD CONSTRAINT fk_marca_producto FOREIGN KEY (id_marca_producto)
      REFERENCES marcas (id_marca)
      ON UPDATE NO ACTION
      ON DELETE NO ACTION;

-- 05 Table imagenes
CREATE TABLE IF NOT EXISTS imagenes (
  nombre_imagen varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  id_producto_imagen int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE imagenes
  ADD KEY(id_producto_imagen),
  ADD CONSTRAINT fk_producto_imagen FOREIGN KEY (id_producto_imagen)
      REFERENCES productos (id_producto)
      ON UPDATE NO ACTION
      ON DELETE NO ACTION;

-- 06 Table pedidos (NUEVA)
CREATE TABLE IF NOT EXISTS pedidos (
  id_pedido int NOT NULL AUTO_INCREMENT,
  nombre_comprador varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  fecha_pedido datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total_pedido int NOT NULL,
  estado_pedido varchar(20) COLLATE utf8_unicode_ci DEFAULT 'pendiente',
  PRIMARY KEY(id_pedido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- 07 Table detalle_pedidos (NUEVA)
CREATE TABLE IF NOT EXISTS detalle_pedidos (
  id_detalle int NOT NULL AUTO_INCREMENT,
  id_pedido_detalle int NOT NULL,
  nombre_producto varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  precio_unitario int NOT NULL,
  cantidad int NOT NULL,
  subtotal int NOT NULL,
  PRIMARY KEY(id_detalle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

ALTER TABLE detalle_pedidos
  ADD KEY(id_pedido_detalle),
  ADD CONSTRAINT fk_pedido_detalle FOREIGN KEY (id_pedido_detalle)
      REFERENCES pedidos (id_pedido)
      ON UPDATE CASCADE
      ON DELETE CASCADE;

-- Datos iniciales de ejemplo
INSERT INTO marcas (nombre_marca) VALUES 
('Marca A'), ('Marca B'), ('Marca C');

INSERT INTO productos (nombre_producto, descripcion_producto, valor_producto, cantidad_producto, id_marca_producto) VALUES
('Balanza Digital Compacta', 'Perfecta para uso doméstico', 150000, 50, 1),
('Balanza Profesional de Cocina', 'Para chefs profesionales', 220000, 30, 2),
('Balanza de Precisión', 'Máxima exactitud garantizada', 300000, 20, 3),
('Balanza Industrial', 'Para alto volumen de trabajo', 450000, 15, 1);
