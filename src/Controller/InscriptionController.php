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




            if (
                isset($_POST['Lastname']) &&
                isset($_POST['Firstname']) &&
                isset($_POST['mail']) &&
                isset($_POST['sexe']) &&
                isset($_POST['pswd'])
            ) {
                $user = array_map('trim', $_POST);
                $lastName = htmlentities($user['Lastname']);
                $firstName = htmlentities($user['Firstname']);
                $email = htmlentities($user['mail']);
                $sexe = htmlentities($user['sexe']);
                $password = htmlentities($user['pswd']);
            }
            $manager = new InscriptionManager();
            $manager->insert($lastName, $firstName, $email, $password, $sexe, $birthday);
            $userId = $manager->selectOne($email, $password);//recuperation de l'id.
            $_SESSION ['userId'] = $userId ['id'];
                header('location:/Profile/show');
        }

        return $this->twig->render('Inscription/form.html.twig');
    }
}
