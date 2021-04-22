<?php

namespace App\Controller;

use App\Model\ProfileManager;

/*explique présence ConnectMAnager*/
/*session_start();*/


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
            $id =  $_SESSION ['userId'];


            $profileManager = new ProfileManager();
            $profileManager->update($email, $password, $birthday, $id);
            header('Location:/SoughtProfile/researchDisplay');
        }



        return $this->twig->render('Profiles/profile.html.twig');
    }
}
