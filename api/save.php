<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Metodo non consentito."]);
    exit;
}

$raw = file_get_contents("php://input");
$input = json_decode($raw ?: "{}", true);
$file = isset($input["file"]) ? (string) $input["file"] : "";
$data = $input["data"] ?? null;
$allowed = ["prenotazioni", "impostazioni"];

if (!in_array($file, $allowed, true)) {
    http_response_code(400);
    echo json_encode(["error" => "File non valido."]);
    exit;
}

if ($file === "prenotazioni" && !is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "Formato prenotazioni non valido."]);
    exit;
}

if ($file === "impostazioni" && !is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "Formato impostazioni non valido."]);
    exit;
}

$dataDir = dirname(__DIR__) . "/data";
$target = $dataDir . "/" . $file . ".json";
$backup = $target . ".backup.json";

if (!is_dir($dataDir) && !mkdir($dataDir, 0755, true) && !is_dir($dataDir)) {
    http_response_code(500);
    echo json_encode(["error" => "Impossibile creare la cartella data."]);
    exit;
}

$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($json === false) {
    http_response_code(500);
    echo json_encode(["error" => "Errore codifica JSON."]);
    exit;
}
$json .= "\n";

if (is_file($target)) {
    @copy($target, $backup);
}

if (file_put_contents($target, $json, LOCK_EX) === false) {
    http_response_code(500);
    echo json_encode(["error" => "Scrittura file fallita."]);
    exit;
}

echo json_encode(["ok" => true, "file" => $file]);
