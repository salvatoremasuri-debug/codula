<?php
declare(strict_types=1);

require_once dirname(__DIR__) . "/includes/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    agenda_send_json(405, ["error" => "Metodo non consentito."]);
}

$input = agenda_read_json_body();
$file = isset($input["file"]) ? (string) $input["file"] : "";
$data = $input["data"] ?? null;

if ($file === "ping") {
    agenda_send_json(400, ["error" => "File non valido."]);
}

agenda_require_auth();
agenda_write_data_file($file, $data);
agenda_send_json(200, ["ok" => true, "file" => $file]);
