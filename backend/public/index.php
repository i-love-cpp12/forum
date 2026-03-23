<?php
declare(strict_types = 1);

require_once(__DIR__ . "/../autoload.php");

use src\Infrastructure\Http\Respond;

Respond::json(["dziala"]);