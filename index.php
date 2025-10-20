<?php
session_start();

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto al carrito
if (isset($_POST['agregar'])) {
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];

    if (isset($_SESSION['carrito'][$producto])) {
        $_SESSION['carrito'][$producto]['cantidad']++;
    } else {
        $_SESSION['carrito'][$producto] = [
            'precio' => $precio,
            'cantidad' => 1
        ];
    }
}

// Eliminar producto individual
if (isset($_POST['eliminar'])) {
    $producto = $_POST['eliminar'];
    unset($_SESSION['carrito'][$producto]);
}

// Vaciar carrito
if (isset($_POST['vaciar'])) {
    $_SESSION['carrito'] = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MiTienda - Balanza</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        header { background-color: #025b34; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { margin: 0; font-size: 20px; }
        nav a { color: white; text-decoration: none; margin-left: 15px; }
        nav a:hover { text-decoration: underline; }

        .hero {
            background-color: #888;
            color: white;
            text-align: center;
            padding: 100px 0;
        }
        .hero h2 { font-size: 28px; margin-bottom: 10px; }
        .hero button { background: #ffcc00; border: none; padding: 10px 20px; cursor: pointer; font-weight: bold; }

        .sobre, .productos, .carrito { padding: 40px 20px; text-align: center; }

        .productos-grid {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .producto {
            background: white;
            padding: 15px;
            width: 220px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            border-radius: 6px;
        }
        .producto img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }
        .producto h3 { margin: 10px 0 5px; }
        .producto p { margin: 5px 0; }
        .producto button {
            background: #025b34;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 4px;
        }
        .producto button:hover { background: #038e52; }

        table { margin: 20px auto; border-collapse: collapse; width: 80%; max-width: 800px; }
        table th, table td { padding: 10px; border: 1px solid #ccc; }
        table th { background: #025b34; color: white; }
        table button {
            background: #c0392b;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
        table button:hover { background: #e74c3c; }

        .vaciar-btn {
            background: #999;
            margin-top: 10px;
        }
        .vaciar-btn:hover { background: #777; }

        footer {
            background-color: #025b34;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<header>
    <h1>MiTienda</h1>
    <nav>
        <a href="#">Inicio</a>
        <a href="#">Productos</a>
        <!-- Se eliminó el enlace de Ofertas -->
        <a href="#">Contacto</a>
    </nav>
</header>

<section class="hero">
    <h2>Balanza de Comida Profesionales</h2>
    <p>Precisión y calidad para tu negocio o cocina</p>
    <button>Ver productos</button>
</section>

<section class="sobre">
    <h2>Sobre Nosotros</h2>
    <p>Somos especialistas en balanzas de comida de alta precisión, diseñadas para negocios y hogares que buscan calidad y confianza.</p>
</section>

<section class="productos">
    <h2>Nuestros Productos</h2>
    <div class="productos-grid">

        <div class="producto">
            <img src="assets/img/balanza1.png" alt="Balanza Digital Compacta">
            <h3>Balanza Digital Compacta</h3>
            <p>$150.000</p>
            <form method="POST">
                <input type="hidden" name="producto" value="Balanza Digital Compacta">
                <input type="hidden" name="precio" value="150000">
                <button type="submit" name="agregar">Comprar</button>
            </form>
        </div>

        <div class="producto">
            <img src="assets/img/balanza2.png" alt="Balanza Profesional de Cocina">
            <h3>Balanza Profesional de Cocina</h3>
            <p>$220.000</p>
            <form method="POST">
                <input type="hidden" name="producto" value="Balanza Profesional de Cocina">
                <input type="hidden" name="precio" value="220000">
                <button type="submit" name="agregar">Comprar</button>
            </form>
        </div>

        <div class="producto">
            <img src="assets/img/balanza3.png" alt="Balanza de Precisión">
            <h3>Balanza de Precisión</h3>
            <p>$300.000</p>
            <form method="POST">
                <input type="hidden" name="producto" value="Balanza de Precisión">
                <input type="hidden" name="precio" value="300000">
                <button type="submit" name="agregar">Comprar</button>
            </form>
        </div>

        <div class="producto">
            <img src="assets/img/balanza4.png" alt="Balanza Industrial">
            <h3>Balanza Industrial</h3>
            <p>$450.000</p>
            <form method="POST">
                <input type="hidden" name="producto" value="Balanza Industrial">
                <input type="hidden" name="precio" value="450000">
                <button type="submit" name="agregar">Comprar</button>
            </form>
        </div>

    </div>
</section>

<section class="carrito">
    <h2>🛒 Carrito de Compras</h2>

    <?php if (empty($_SESSION['carrito'])): ?>
        <p>Tu carrito está vacío.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acción</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION['carrito'] as $nombre => $item):
                $subtotal = $item['precio'] * $item['cantidad'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?php echo $nombre; ?></td>
                <td>$<?php echo number_format($item['precio'], 0, ',', '.'); ?></td>
                <td><?php echo $item['cantidad']; ?></td>
                <td>$<?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                <td>
                    <form method="POST">
                        <button type="submit" name="eliminar" value="<?php echo $nombre; ?>">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" align="right"><strong>Total:</strong></td>
                <td colspan="2"><strong>$<?php echo number_format($total, 0, ',', '.'); ?></strong></td>
            </tr>
        </table>
        <form method="POST">
            <button type="submit" name="vaciar" class="vaciar-btn">Vaciar Carrito</button>
        </form>
    <?php endif; ?>
</section>

<footer>
    <p>© 2025 MiTienda de Balanzas. Todos los derechos reservados.</p>
</footer>

</body>
</html>
