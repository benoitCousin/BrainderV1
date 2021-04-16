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
            $password = $_POST['password'];
            $birthday = $_POST['birthday'];

            $manager = new InscriptionManager();
            $manager->insert($lastName, $firstName, $email, $password, $sexe, $birthday);
        }
        return $this->twig->render('Inscription/form.html.twig');
    }
}
