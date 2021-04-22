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
