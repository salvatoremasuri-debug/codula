<?php
declare(strict_types=1);

require_once dirname(__DIR__) . "/includes/bootstrap.php";

agenda_start_session();
$_SESSION = [];
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

agenda_send_json(200, ["ok" => true]);
