<?php

namespace App\Controller;

use App\Model\ContactManager;

class ContactController extends AbstractController
{
    public function create(): string
    {
        if (
            isset($_POST['subject']) &&
            isset($_POST['message']) &&
            isset($_POST['profilId'])
        ) {
            $data = array_map('trim', $_POST);
            $subject = htmlentities($data['subject']);
            $message = htmlentities($data['message']);
            $profilId = htmlentities($data['profilId']);

            $manager = new ContactManager();
            $manager->insert($subject, $message, $profilId);
        }

        $userId = $_SESSION['userId'];
        return $this->twig->render('Contact/contactForm.html.twig', ['userId' => $userId]);
    }

    public function signal($profileId, $profilePseudo): string
    {
        $userId = $_SESSION['userId'];

        return $this->twig->render('Contact/contactForm.html.twig', [
            'userId' => $userId,
            'signalId' => $profileId,
            'signalPseudo' => $profilePseudo
        ]);
    }
}
