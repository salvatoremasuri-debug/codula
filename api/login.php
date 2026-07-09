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
$username = trim((string) ($input["username"] ?? ""));
$password = (string) ($input["password"] ?? "");
$config = agenda_load_auth_config();
$expectedUser = (string) ($config["username"] ?? "admin");

if ($username === "" || $password === "" || $username !== $expectedUser || !agenda_verify_password($password, $config)) {
    agenda_send_json(401, ["error" => "Username o password non validi."]);
}

agenda_start_session();
session_regenerate_id(true);
$_SESSION["agenda_auth"] = true;
$_SESSION["agenda_user"] = $expectedUser;

agenda_send_json(200, ["ok" => true, "username" => $expectedUser]);
