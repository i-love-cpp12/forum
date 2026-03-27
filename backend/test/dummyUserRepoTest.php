<?php

require_once(__DIR__ . "/../autoload.php");

use src\Infrastructure\Repository\Dummy\DummyUserRepository;
use src\Infrastructure\Http\Respond;

$repo = new DummyUserRepository();

sleep(1);
Respond::json(
    [
        "all" => $repo->getAllUsers(),
        "email(0)" => $repo->getUserByEmail("oliwier0@gmail.com"),
        "by id(0)" => $repo->getUserById(0),
        "delete" => $repo->deleteUser(0),
        "after delete by id" => $repo->getUserById(0),
        "after delete by email" => $repo->getUserByEmail("oliwier0@gmail.com"),
        "token for(0)" => $repo->getActiveTokensForUser(0),
        "token for(1)" => $repo->getActiveTokensForUser(1),
        "token for(20)" => $repo->getActiveTokensForUser(20),
        "time" => time()
    ]
);