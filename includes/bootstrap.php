<?php
declare(strict_types=1);

function agenda_send_json(int $status, array $payload): void
{
    http_response_code($status);
    header("Content-Type: application/json; charset=utf-8");
    header("Cache-Control: no-store");
    echo json_encode($payload);
    exit;
}

function agenda_start_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_set_cookie_params([
            "lifetime" => 0,
            "path" => "/",
            "secure" => !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
            "httponly" => true,
            "samesite" => "Lax"
        ]);
        session_start();
    }
}

function agenda_load_auth_config(): array
{
    $authFile = dirname(__DIR__) . "/config/auth.php";
    $sampleFile = dirname(__DIR__) . "/config/auth.sample.php";
    $path = is_file($authFile) ? $authFile : $sampleFile;
    if (!is_file($path)) {
        return ["username" => "admin", "password" => "agenda2026"];
    }
    $config = require $path;
    return is_array($config) ? $config : [];
}

function agenda_verify_password(string $password, array $config): bool
{
    if (!empty($config["password_hash"]) && is_string($config["password_hash"])) {
        return password_verify($password, $config["password_hash"]);
    }
    if (!empty($config["password"]) && is_string($config["password"])) {
        return hash_equals((string) $config["password"], $password);
    }
    return false;
}

function agenda_is_authenticated(): bool
{
    agenda_start_session();
    return !empty($_SESSION["agenda_auth"]) && $_SESSION["agenda_auth"] === true;
}

function agenda_require_auth(): void
{
    if (!agenda_is_authenticated()) {
        agenda_send_json(401, ["error" => "Accesso non autorizzato."]);
    }
}

function agenda_read_json_body(): array
{
    $raw = file_get_contents("php://input");
    $input = json_decode($raw ?: "{}", true);
    return is_array($input) ? $input : [];
}

function agenda_data_path(string $file): string
{
    $allowed = ["prenotazioni", "impostazioni"];
    if (!in_array($file, $allowed, true)) {
        agenda_send_json(400, ["error" => "File non valido."]);
    }
    return dirname(__DIR__) . "/data/" . $file . ".json";
}

function agenda_validate_data(string $file, $data): void
{
    if ($file === "prenotazioni" && !is_array($data)) {
        agenda_send_json(400, ["error" => "Formato prenotazioni non valido."]);
    }
    if ($file === "impostazioni" && !is_array($data)) {
        agenda_send_json(400, ["error" => "Formato impostazioni non valido."]);
    }
}

function agenda_write_data_file(string $file, $data): void
{
    agenda_validate_data($file, $data);
    $dataDir = dirname(__DIR__) . "/data";
    $target = agenda_data_path($file);
    $backup = $target . ".backup.json";

    if (!is_dir($dataDir) && !mkdir($dataDir, 0755, true) && !is_dir($dataDir)) {
        agenda_send_json(500, ["error" => "Impossibile creare la cartella data."]);
    }

    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        agenda_send_json(500, ["error" => "Errore codifica JSON."]);
    }
    $json .= "\n";

    if (is_file($target)) {
        @copy($target, $backup);
    }

    if (file_put_contents($target, $json, LOCK_EX) === false) {
        agenda_send_json(500, ["error" => "Scrittura file fallita."]);
    }
}

function agenda_read_data_file(string $file, $fallback)
{
    $target = agenda_data_path($file);
    if (!is_file($target)) {
        return $fallback;
    }
    $raw = file_get_contents($target);
    if ($raw === false) {
        agenda_send_json(500, ["error" => "Lettura file fallita."]);
    }
    $decoded = json_decode($raw, true);
    if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
        agenda_send_json(500, ["error" => "JSON non valido in " . $file . "."]);
    }
    return $decoded ?? $fallback;
}
