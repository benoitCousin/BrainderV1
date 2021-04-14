<?php

namespace App\Controller;

use App\Model\SoughtProfileManager;

class SoughtProfileController extends AbstractController
{
    // principal display to test US57:
    public function resultResearch()
    {
        session_destroy();
        session_start();
        $_SESSION['userId'] = 6; // I am the user test with id = 6 which have done the research
        $userID = $_SESSION['userId'];
        $profileManager = new SoughtProfileManager();
        $result = $profileManager->research($userID);
        $_SESSION['resultResearch'] = $result;
        return $this->twig->render('SoughtProfile/soughtProfile.html.twig', ['session' => $_SESSION]);
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
        header('Location:/soughtProfile/profileDisplay/');
    }

    public function profileDisplay()
    {
        return $this->twig->render('SoughtProfile/soughtProfile.html.twig', ['session' => $_SESSION]);
    }
}
