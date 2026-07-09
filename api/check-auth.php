<?php
declare(strict_types=1);

require_once dirname(__DIR__) . "/includes/bootstrap.php";

$config = agenda_load_auth_config();
$authenticated = agenda_is_authenticated();

agenda_send_json(200, [
    "ok" => true,
    "authenticated" => $authenticated,
    "username" => $authenticated ? (string) ($_SESSION["agenda_user"] ?? $config["username"] ?? "admin") : null,
    "authRequired" => true
]);
