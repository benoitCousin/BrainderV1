<?php

namespace App\Model;

class SoughtProfileManager extends AbstractManager
{
    public const TABLE = 'profiles';

    public function selectOneMatch(int $searchingId, int $foundId)
    {
        $query = 'SELECT * FROM ' . APP_DB_NAME . '.match WHERE (profile1Id = ' . $searchingId .
            ' AND profile2Id = ' . $foundId . ') 
            OR (profile2Id = ' . $searchingId . ' AND profile1Id = ' . $foundId . ')';
        $statement = $this->pdo->query($query);
        return $statement->fetch();
    }

    public function insertMatch(int $searchingId, int $foundId, string $statusSearching)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . APP_DB_NAME . ".match " .
            "(`profile1Id`, `profile2Id`, `profile1Status`, `date`, `matchStatus`)
            VALUES (:profile1Id, :profile2Id, :profile1Status, Now(), 0)");
        $statement->bindValue('profile1Id', $searchingId, \PDO::PARAM_INT);
        $statement->bindValue('profile2Id', $foundId, \PDO::PARAM_INT);
        $statement->bindValue('profile1Status', $statusSearching, \PDO::PARAM_STR);

        return $statement->execute();
    }

    public function finishMatch(int $searchingId, int $foundId, string $statusSearching): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . APP_DB_NAME . ".match 
            SET `profile2Status` = :statusSearching, `date` = Now(), `matchStatus` = :matchStatus " .
            "WHERE profile2Id=:searchingId AND profile1Id=:foundId");
        $statement->bindValue('statusSearching', $statusSearching, \PDO::PARAM_STR);
        $statement->bindValue('searchingId', $searchingId, \PDO::PARAM_INT);
        $statement->bindValue('foundId', $foundId, \PDO::PARAM_INT);
        if ($statusSearching === 'accept') {
            $statement->bindValue('matchStatus', 1, \PDO::PARAM_STR);
        } else {
            $statement->bindValue('matchStatus', 0, \PDO::PARAM_STR);
        }

        return $statement->execute();
    }

    public function research(int $userID, int $gender)
    {
        $query = 'SELECT id, pseudo, catchPhrase FROM profiles 
            WHERE id != ' . $userID . ' AND gender = ' . $gender . ';';
        $statement = $this->pdo->query($query);

        return $statement->fetchAll();
    }
}
