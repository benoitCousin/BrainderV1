<?php

namespace App\Model;

class ContactManager extends AbstractManager
{
    public const TABLE = 'comment';

    public function insert($subject, $message, $profilId)
    {
        $query = 'INSERT INTO comment (subject, message, profilId)
                    VALUES (:subject, :message, :profilId);';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':subject', $subject, \PDO::PARAM_STR);
        $statement->bindValue(':message', $message, \PDO::PARAM_STR);
        $statement->bindValue(':profilId', $profilId, \PDO::PARAM_INT);
        $statement->execute();
    }
}
