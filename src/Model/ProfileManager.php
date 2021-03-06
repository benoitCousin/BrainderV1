<?php

namespace App\Model;

class ProfileManager extends AbstractManager
{
    public const TABLE = 'profiles';

    public function insert($lastName, $firstName, $email, $password, $sexe, $birthday, $town)
    {
        $query = 'INSERT INTO profiles (lastname, firstname, email, pswd, gender, birthday, town)
                    VALUES (:lastname, :firstname, :email, :pswd, :gender, :birthday, :town);';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':lastname', $lastName, \PDO::PARAM_STR);
        $statement->bindValue(':firstname', $firstName, \PDO::PARAM_STR);
        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->bindValue(':pswd', $password, \PDO::PARAM_STR);
        $statement->bindValue(':gender', $sexe, \PDO::PARAM_BOOL);
        $statement->bindParam(':birthday', $birthday, \PDO::PARAM_STR);
        $statement->bindValue(':town', $town, \PDO::PARAM_STR);
        $statement->execute();
    }



    public function selectOne($email, $password)
    {
        $statement = $this->pdo->prepare("SELECT  id from profiles WHERE email=:email AND  pswd=:pswd");
        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->bindValue(':pswd', $password, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    public function update($email, $password, $birthday, $pseudo, $town, $catchPhrase, $searchGender, $id): bool
    {
        $statement = $this->pdo->prepare("UPDATE  profiles SET email=:email, pswd=:pswd, 
            birthday=:birthday, pseudo=:pseudo ,town=:town, catchPhrase=:catchPhrase, 
            searchGender=:searchGender WHERE id =:id");
        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->bindValue(':pswd', $password, \PDO::PARAM_STR);
        $statement->bindParam(':birthday', $birthday, \PDO::PARAM_STR);
        $statement->bindParam(':pseudo', $pseudo, \PDO::PARAM_STR);
        $statement->bindValue(':town', $town, \PDO::PARAM_STR);
        $statement->bindValue(':catchPhrase', $catchPhrase, \PDO::PARAM_STR);
        $statement->bindValue(':searchGender', $searchGender, \PDO::PARAM_INT);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);


        return $statement->execute();
    }


    public function uploadPictures($imgNom, $profilId): void
    {
        $query = 'INSERT INTO pictures (img_nom, profilId)
            VALUES (:img_nom, :profilId);';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':img_nom', $imgNom, \PDO::PARAM_STR);
        $statement->bindValue(':profilId', $profilId, \PDO::PARAM_INT);
        $statement->execute();
    }


    public function showPictures($profilId): array
    {
        $query = 'SELECT img_nom, img_id FROM pictures WHERE profilId = ' . $profilId . ';';
        return $this->pdo->query($query)->fetchAll();
    }

    public function deletePicture(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM pictures WHERE img_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectOneByPseudo(string $pseudo)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT pseudo, id FROM " . static::TABLE . " WHERE pseudo=:pseudo");
        $statement->bindValue('pseudo', $pseudo, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }
}
