<?php

namespace App\Controller;

use App\Model\InscriptionManager;
use App\Model\ConnectManager;

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
            $acceptCGV = $_POST['acceptCGV'];




            if (
                isset($_POST['Lastname']) &&
                isset($_POST['Firstname']) &&
                isset($_POST['mail']) &&
                isset($_POST['sexe']) &&
                isset($_POST['pswd']) &&
                isset($_POST['acceptCGV'])
            ) {
                $user = array_map('trim', $_POST);
                $lastName = htmlentities($user['Lastname']);
                $firstName = htmlentities($user['Firstname']);
                $email = htmlentities($user['mail']);
                $sexe = htmlentities($user['sexe']);
                $password = htmlentities($user['pswd']);
                $acceptCGV = htmlentities($user['acceptCGV']);
            }

            $errors = [];
            $mailManager = new ConnectManager();
            $existingMail = $mailManager->selectOneByMail($email);

            if ($existingMail != false) {
                $errors[] = "Erreur: cette adresse e-mail est déjà associée à un compte. " .
                "Veuillez utiliser une autre adresse e-mail.";
                return $this->twig->render('Inscription/form.html.twig', ['errors' => $errors]);
            }


            $manager = new InscriptionManager();
            $manager->insert($lastName, $firstName, $email, $password, $sexe, $birthday, $acceptCGV);
            $userId = $manager->selectOne($email, $password);//recuperation de l'id.
            $_SESSION ['userId'] = $userId ['id'];
                header('location:/Avatar/avatarCreate');
        }

        return $this->twig->render('Inscription/form.html.twig');
    }
}
