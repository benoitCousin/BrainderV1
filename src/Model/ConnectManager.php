<?php

namespace App\Model;

class ConnectManager extends AbstractManager
{
    public const TABLE = 'profiles';

    /* Get one row from database by eMAIL. */
    public function selectOneByMail(string $email)
    {
        // prepared request /*retour password*/
        $statement = $this->pdo->prepare("SELECT pswd, id FROM " . static::TABLE . " WHERE email=:email");
        $statement->bindValue('email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch(); /*renvoie password ET id*/
    }
}
