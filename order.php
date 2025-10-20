<?php
// order.php - Guarda pedidos en base de datos MySQL
header('Content-Type: application/json');

// Configuración de base de datos
$host = 'localhost';
$dbname = 'tiendaonline';
$username = 'root';
$password = '';

try {
    // Conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Leer datos JSON
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    
    if(!$data || !isset($data['buyer']) || !isset($data['cart']) || empty($data['cart'])){
        http_response_code(400);
        echo json_encode(['success'=>false, 'error'=>'Datos inválidos']);
        exit;
    }
    
    $buyer = trim($data['buyer']);
    $cart = $data['cart'];
    $total = isset($data['total']) ? (int)$data['total'] : 0;
    
    // Calcular total si no viene
    if($total === 0){
        foreach($cart as $item){
            $total += (int)$item['price'] * (int)$item['quantity'];
        }
    }
    
    // Iniciar transacción
    $pdo->beginTransaction();
    
    // Insertar pedido
    $stmt = $pdo->prepare("
        INSERT INTO pedidos (nombre_comprador, total_pedido, estado_pedido) 
        VALUES (:buyer, :total, 'pendiente')
    ");
    $stmt->execute([
        ':buyer' => $buyer,
        ':total' => $total
    ]);
    
    $pedidoId = $pdo->lastInsertId();
    
    // Insertar detalles del pedido
    $stmtDetalle = $pdo->prepare("
        INSERT INTO detalle_pedidos 
        (id_pedido_detalle, nombre_producto, precio_unitario, cantidad, subtotal) 
        VALUES (:pedido_id, :nombre, :precio, :cantidad, :subtotal)
    ");
    
    foreach($cart as $item){
        $precio = (int)$item['price'];
        $cantidad = (int)$item['quantity'];
        $subtotal = $precio * $cantidad;
        
        $stmtDetalle->execute([
            ':pedido_id' => $pedidoId,
            ':nombre' => $item['name'],
            ':precio' => $precio,
            ':cantidad' => $cantidad,
            ':subtotal' => $subtotal
        ]);
    }
    
    // Confirmar transacción
    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'id' => $pedidoId,
        'total' => $total,
        'items' => count($cart)
    ]);
    
} catch(PDOException $e){
    // Revertir en caso de error
    if(isset($pdo) && $pdo->inTransaction()){
        $pdo->rollBack();
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => 'Error de base de datos: ' . $e->getMessage()
    ]);
} catch(Exception $e){
    if(isset($pdo) && $pdo->inTransaction()){
        $pdo->rollBack();
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
}
?>