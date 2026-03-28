<?php

require_once(__DIR__ . "/../autoload.php");

use src\Infrastructure\Repository\Dummy\DummyUserRepository;
use src\Infrastructure\Http\Respond;
use src\Domain\Entity\User;
use src\Domain\Entity\Token;

$repo = new DummyUserRepository();

sleep(1);
Respond::json(
    [
        "email(0)" => $repo->getUserByEmail("oliwier0@gmail.com"),
        "by id(0)" => $repo->getUserById(0),
        "by id(100)" => $repo->getUserById(100),
        "by email(askdjhkajhsd)" => $repo->getUserByEmail("askdjhkajhsd"),
        "update(0)" => $repo->saveUser(new User(0, "oliwier0Updated", "oliwier0Updated@gmail.com", hash("sha256", "new pass"))),
        "by id after update(0)" => $repo->getUserById(0),
        "delete" => $repo->deleteUser(0),
        "after delete by id" => $repo->getUserById(0),
        "after delete by email" => $repo->getUserByEmail("oliwier0@gmail.com"),
        "token for(0)" => $repo->hasUserActiveToken(0),
        "token for(1)" => $repo->hasUserActiveToken(1),
        "token for(20)" => $repo->hasUserActiveToken(20),
        "add new token for user id (20)" => $repo->activateToken(new Token(null, 20, hash("sha256", "new token"), null)),
        "token for(20) after insert" => $repo->hasUserActiveToken(20),
        "token deactivate token id (21)" => $repo->deactivateTokensForUser(20),
        "token for (20) after deativate" => $repo->hasUserActiveToken(20),
        "time" => time()
    ]
);