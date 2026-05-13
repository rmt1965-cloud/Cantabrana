<?php
$archivo = '../analytics/eventos.json';

$data = json_decode(file_get_contents('php://input'), true);

$data['timestamp'] = date('c');
$data['evento'] = 'finalizacion';

$contenido = [];

if(file_exists($archivo)) {
    $contenido = json_decode(file_get_contents($archivo), true);
}

$contenido[] = $data;

file_put_contents(
    $archivo,
    json_encode(
        $contenido,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    )
);

echo json_encode(['ok'=>true]);
?>
