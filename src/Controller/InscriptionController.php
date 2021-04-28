<?php

namespace App\Controller;

use App\Model\InscriptionManager;

class InscriptionController extends AbstractController
{
    public function create(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lastName = $_POST['Lastname'];
            $firstName = $_POST['Firstname'];
            $email = $_POST['mail'];
            $sexe = $_POST['sexe'];
            $password = $_POST['pswd'];
            $birthday = $_POST['birthday'];

            $manager = new InscriptionManager();
            $manager->insert($lastName, $firstName, $email, $password, $sexe, $birthday);
            $userId = $manager->selectOne($email, $password);//recuperation de l'id.
            $_SESSION ['userId'] = $userId ['id'];
                header('location:/Profile/show');
        }

        return $this->twig->render('Inscription/form.html.twig');
    }
}
