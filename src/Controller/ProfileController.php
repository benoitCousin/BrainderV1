<?php

namespace App\Controller;

use App\Model\ProfileManager;

/*explique présence ConnectMAnager*/



class ProfileController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('Profiles/profile.html.twig');
    }

    public function show()
    {
        $userId = $_SESSION ['userId'] ;

        $profileManager = new ProfileManager();
        $user = $profileManager->selectOneById($userId);






        return $this->twig->render('Profiles/profile.html.twig', ['user' => $user]);
    }

    public function update(): string
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = array_map('trim', $_POST);
            $email = htmlentities($user['mail']);
            $birthday = htmlentities($user['birthday']);
            $password = htmlentities($user['pswd']);
            $pseudo = htmlentities($user['pseudo']);
            $id =  $_SESSION ['userId'];

            $uploadDir = 'assets/images/profile/';

            $imgNom = basename($_FILES['picture']['name']);




            $uploadFile = $uploadDir . basename($_FILES['picture']['name']);




            if (!empty($imgNom)) {
                $profileManager = new ProfileManager();
                $profileManager->uploadPictures($imgNom, $id);
            }

            move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile);



            $profileManager = new ProfileManager();
            $profileManager->update($email, $password, $birthday, $pseudo, $id);
            header('Location:/SoughtProfile/selectShow');
        }



        return $this->twig->render('Profiles/profile.html.twig');
    }
}
