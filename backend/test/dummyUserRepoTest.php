<?php

require_once(__DIR__ . "/../autoload.php");

use src\Infrastructure\Repository\Dummy\DummyUserRepository;
use src\Infrastructure\Http\Respond;
use src\Domain\Entity\User;
$repo = new DummyUserRepository();

sleep(1);
Respond::json(
    [
        "all" => $repo->getAllUsers(),
        "email(0)" => $repo->getUserByEmail("oliwier0@gmail.com"),
        "by id(0)" => $repo->getUserById(0),
        "by id(100)" => $repo->getUserById(100),
        "by email(askdjhkajhsd)" => $repo->getUserByEmail("askdjhkajhsd"),
        "update(0)" => $repo->save(new User(0, "oliwier0Updated", "oliwier0Updated@gmail.com", hash("sha256", "new pass"))),
        "by id after update(0)" => $repo->getUserById(0),
        "delete" => $repo->deleteUser(0),
        "after delete by id" => $repo->getUserById(0),
        "after delete by email" => $repo->getUserByEmail("oliwier0@gmail.com"),
        "token for(0)" => $repo->getActiveTokensForUser(0),
        "token for(1)" => $repo->getActiveTokensForUser(1),
        "token for(20)" => $repo->getActiveTokensForUser(20),
        "time" => time()
    ]
);