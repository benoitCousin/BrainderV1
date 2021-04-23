<?php

namespace App\Controller;

use App\Model\SoughtProfileManager;

class SoughtProfileController extends AbstractController
{
    public function resultResearch()
    {
        $soughtGender = $_SESSION['soughtGender'];
        $userID = $_SESSION['userId'];
        $profileManager = new SoughtProfileManager();
        $resultResearch = $profileManager->research($userID, $soughtGender);

        // aggregate all pictures name in an array by profile
        for ($i = count($resultResearch) - 1; $i > 0; $i--) {
            if ($resultResearch[$i]['id'] == $resultResearch[$i - 1]['id']) {
                $resultResearch[$i - 1]['img_nom'] .= ' ' . $resultResearch[$i]['img_nom'];
                array_splice($resultResearch, $i, 1);
            } else {
                $resultResearch[$i]['img_nom'] = explode(" ", $resultResearch[$i]['img_nom']);
            }
        }
        $resultResearch[0]['img_nom'] = explode(" ", $resultResearch[0]['img_nom']);

        $_SESSION['resultResearch'] = $resultResearch;

        return $this->twig->render('SoughtProfile/soughtProfile.html.twig', [
            'resultResearch' => $resultResearch,
            'userId' => $userID
        ]);
    }

    public function matchUpdate(int $searchingId, int $foundId, string $statusSearching)
    {
        $profileManager = new SoughtProfileManager();
        $soughtMatch = $profileManager->selectOneMatch($searchingId, $foundId);

        if ($soughtMatch) {
            $profileManager->finishMatch($searchingId, $foundId, $statusSearching);
        } else {
            $profileManager->insertMatch($searchingId, $foundId, $statusSearching);
        }
        array_shift($_SESSION['resultResearch']);
        $resultResearch = $_SESSION['resultResearch'];

        return $this->twig->render('SoughtProfile/soughtProfile.html.twig', [
            'resultResearch' => $resultResearch,
            'userId' => $searchingId
        ]);
    }

    public function researchDisplay()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['gender'])) {
                $errors[] = "Veuillez choisir le genre à rechercher.";
            }

            if (empty($errors)) {
                $data = array_map('trim', $_POST);

                $_SESSION['soughtGender'] = htmlentities($data['gender']);
                header('Location:/soughtProfile/resultResearch/ ');
            }
        }

        return $this->twig->render('SoughtProfile/research.html.twig', ['errors' => $errors]);
    }
}
