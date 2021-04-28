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
            "(`profile1Id`, `profile2Id`, `profile1Status`, `date1Status`, `matchStatus`)
            VALUES (:profile1Id, :profile2Id, :profile1Status, NOW(), 0)");
        $statement->bindValue('profile1Id', $searchingId, \PDO::PARAM_INT);
        $statement->bindValue('profile2Id', $foundId, \PDO::PARAM_INT);
        $statement->bindValue('profile1Status', $statusSearching, \PDO::PARAM_STR);

        return $statement->execute();
    }

    public function finishMatch(int $searchingId, int $foundId, string $statusSearching): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . APP_DB_NAME . ".match 
            SET `profile2Status` = :statusSearching, `date2Status` = NOW(), `matchStatus` = :matchStatus " .
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
        $query = 'SELECT id, pseudo, catchPhrase, img_nom, town FROM profiles LEFT JOIN pictures 
        ON profiles.id = pictures.profilId WHERE id != ' . $userID . ' AND gender = ' . $gender . ';';
        $statement = $this->pdo->query($query);

        return $statement->fetchAll();
    }

    public function selectList(int $userID)
    {
        $query = '(SELECT profiles.id, pseudo, profile2Status choix, date1Status dateSelection, matchStatus
            FROM profiles JOIN ' . APP_DB_NAME . '.match ON profiles.id = catch.match.profile2Id
            WHERE catch.match.profile1Id = ' . $userID . ' AND catch.match.profile1Status = "accept")
            UNION
            (SELECT profiles.id, pseudo, profile1Status ,date2Status, matchStatus FROM profiles
            JOIN ' . APP_DB_NAME . '.match ON profiles.id = catch.match.profile1Id
            WHERE catch.match.profile2Id= ' . $userID . ' AND catch.match.profile2Status = "accept")
            ORDER BY dateSelection DESC;';
        $statement = $this->pdo->query($query);

        return $statement->fetchAll();
    }

    public function updateRefuseMatch(
        int $profil1Id,
        int $profil2Id,
        string $profileStatus,
        string $dateStatus
    ) {
        $statement = $this->pdo->prepare('UPDATE ' . APP_DB_NAME . '.match
            SET ' . $profileStatus . ' = "refuse", ' . $dateStatus . ' = NOW(), matchStatus = 0
            WHERE profile2Id=:profile2Id AND profile1Id=:profile1Id');
        $statement->bindValue('profile1Id', $profil1Id, \PDO::PARAM_INT);
        $statement->bindValue('profile2Id', $profil2Id, \PDO::PARAM_INT);

        return $statement->execute();
    }
}
