<?php

namespace App\Model;

class InscriptionManager extends AbstractManager
{
    public const TABLE = 'profiles';

    public function insert($lastName, $firstName, $email, $sexe, $password, $birthday)
    {
        $query = 'INSERT INTO profiles (lastname, firstname, email, pswd, gender,birthday)
                    VALUES (:lastname, :firstname, :email, :pswd, :sexe, :birthday);';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':lastname', $lastName, \PDO::PARAM_STR);
        $statement->bindValue(':firstname', $firstName, \PDO::PARAM_STR);
        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->bindValue(':pswd', $password, \PDO::PARAM_INT);
        $statement->bindValue(':sexe', $sexe, \PDO::PARAM_BOOL);
        $statement->bindParam(':birthday', $birthday, \PDO::PARAM_STR);
        $statement->execute();
    }
}
