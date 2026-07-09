<?php
declare(strict_types=1);

require_once dirname(__DIR__) . "/includes/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    agenda_send_json(405, ["error" => "Metodo non consentito."]);
}

agenda_require_auth();

$file = isset($_GET["file"]) ? (string) $_GET["file"] : "";
$fallback = $file === "impostazioni" ? [] : [];

$data = agenda_read_data_file($file, $fallback);
agenda_send_json(200, ["ok" => true, "file" => $file, "data" => $data]);
