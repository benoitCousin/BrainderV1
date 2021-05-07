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
            $extensionsOk = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            // Looping all files
            if ($_FILES['files']['name'][0] != '') {
                for ($i = 0; $i < $countFiles; $i++) {
                    $extension = pathinfo($_FILES['files']['name'][$i], PATHINFO_EXTENSION);

                    if (!in_array($extension, $extensionsOk)) {
                        $errors[0] = "L'extension [" . $extension . "] du fichier [" . $_FILES['files']['name'][$i] . "]
                        est invalide. Les types autorisés sont [ ";
                        foreach ($extensionsOk as $ext) {
                            $errors[0] .= $ext . ", ";
                        }
                        $errors[0] .= "].";
                        $userId = $_SESSION ['userId'];
                        $user = $profileManager->selectOneById($userId);
                        $pictures =  $profileManager->showPictures($userId);
                        return $this->twig->render('Profiles/profile.html.twig', [
                            'errors' => $errors,
                            'user' => $user,
                            'pictures' => $pictures
                        ]);
                    }

                    $imgNom = uniqid("") . "." . $extension;
                    $uploadFile = $uploadDir . $imgNom;

                    if (!empty($imgNom)) {
                        $profileManager = new ProfileManager();
                        $profileManager->uploadPictures($imgNom, $id);
                    }

                    move_uploaded_file($_FILES['files']['tmp_name'][$i], $uploadFile);
                }
            }


            $profileManager = new ProfileManager();
            $profileManager->update($email, $password, $birthday, $pseudo, $town, $catchPhrase, $searchGender, $id);
            header('Location:/SoughtProfile/resultResearch');
        }


        return $this->twig->render('Profiles/profile.html.twig');
    }
}
