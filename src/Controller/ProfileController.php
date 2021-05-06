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

        // delete file when push delete button
        if (isset($_POST['delete'])) {
            $dir = substr(__DIR__, 0, -15);
            $file = $dir . $_POST['delete'];
            $imgId = (int)$_POST['deleteId'];

            if (file_exists($file)) {
                $profileManager->deletePicture($imgId);
                unlink($file);
            }
        }

        $user = $profileManager->selectOneById($userId);

        $pictures =  $profileManager->showPictures($userId);


        return $this->twig->render('Profiles/profile.html.twig', ['user' => $user, 'pictures' => $pictures]);
    }

    public function update(): string
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = array_map('trim', $_POST);
            $email = htmlentities($user['mail']);
            $password = htmlentities($user['pswd']);
            $birthday = htmlentities($user['birthday']);
            $pseudo = htmlentities($user['pseudo']);
            $town = htmlentities($user['town']);
            $catchPhrase = htmlentities($user['catchPhrase']);
            $searchGender = htmlentities($user['searchGender']);
            $id =  $_SESSION ['userId'];

            $profileManager = new ProfileManager();
            $existingPseudo = $profileManager->selectOneByPseudo($pseudo);
            $errors = [];

            if ($existingPseudo != false && $existingPseudo['id'] != $id) {
                $errors[] = "Erreur: ce pseudo est déjà utilisé. " .
                "Veuillez en choisir un autre.";
                $userId = $_SESSION ['userId'];
                $user = $profileManager->selectOneById($userId);
                $pictures =  $profileManager->showPictures($userId);
                return $this->twig->render('Profiles/profile.html.twig', [
                    'errors' => $errors,
                    'user' => $user,
                    'pictures' => $pictures
                ]);
            }

            $uploadDir = 'assets/images/profile/';

            // Count total files
            $countFiles = count($_FILES['files']['name']);

            // Looping all files
            for ($i = 0; $i < $countFiles; $i++) {
                $imgNom = basename($_FILES['files']['name'][$i]);
                $uploadFile = $uploadDir . basename($_FILES['files']['name'][$i]);

                if (!empty($imgNom)) {
                    $profileManager = new ProfileManager();
                    $profileManager->uploadPictures($imgNom, $id);
                }

                move_uploaded_file($_FILES['files']['tmp_name'][$i], $uploadFile);
            }


            $profileManager = new ProfileManager();
            $profileManager->update($email, $password, $birthday, $pseudo, $town, $catchPhrase, $searchGender, $id);
            header('Location:/SoughtProfile/resultResearch');
        }



        return $this->twig->render('Profiles/profile.html.twig');
    }
}
