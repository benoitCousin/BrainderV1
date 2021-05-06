<?php

namespace App\Controller;

use App\Model\SoughtProfileManager;

class SoughtProfileController extends AbstractController
{
    private int $userID;
    private SoughtProfileManager $profileManager;

    public function __construct()
    {
        parent::__construct();
        $this->userID = $_SESSION['userId'];
        $this->profileManager = new SoughtProfileManager();
    }

    public function resultResearch()
    {
        $user = $this->profileManager->selectOneById($this->userID);
        $soughtGender = $user['searchGender'];

        // find profiles not to display
        $undisplayProfiles = $this->profileManager->undisplay($this->userID);
        $noPseudoProfiles = $this->profileManager->undisplayNoPseudo();

        // convert multi dimensional array in string
        $undisplayResult = $this->undisplayProfiles($undisplayProfiles, $noPseudoProfiles, $this->userID);

        // search new profiles to see
        $resultResearch = $this->profileManager->research($soughtGender, $undisplayResult);

        // aggregate all pictures name in an array by profile
        $resultResearch = $this->aggregatePictures($resultResearch);

        // distance calculate
        $userTown = $user['town'];
        $resultResearch = $this->distanceCalculate($resultResearch, $userTown);

        $_SESSION['resultResearch'] = $resultResearch;

        return $this->twig->render('SoughtProfile/soughtProfile.html.twig', [
            'resultResearch' => $resultResearch,
            'userId' => $this->userID
        ]);
    }

    public function matchUpdate(int $searchingId, int $foundId, string $statusSearching)
    {
        $soughtMatch = $this->profileManager->selectOneMatch($searchingId, $foundId);

        if ($soughtMatch) {
            $this->profileManager->finishMatch($searchingId, $foundId, $statusSearching);
        } else {
            $this->profileManager->insertMatch($searchingId, $foundId, $statusSearching);
        }
        array_shift($_SESSION['resultResearch']);
        $resultResearch = $_SESSION['resultResearch'];

        return $this->twig->render('SoughtProfile/soughtProfile.html.twig', [
            'resultResearch' => $resultResearch,
            'userId' => $searchingId
        ]);
    }

    public function selectShow()
    {
        $profileList = $this->profileManager->selectList($this->userID);

        return $this->twig->render('Profiles/profilesList.html.twig', [
            'userId' => $this->userID,
            'list' => $profileList
        ]);
    }

    public function deleteSelectProfile(int $profilId)
    {
        $soughtMatch = $this->profileManager->selectOneMatch($profilId, $this->userID);

        if ($soughtMatch['profile1Id'] == $this->userID) {
            $this->profileManager->updateRefuseMatch($this->userID, $profilId, 'profile1Status', 'date1Status');
        } else {
            $this->profileManager->updateRefuseMatch($profilId, $this->userID, 'profile2Status', 'date2Status');
        }

        $profileList = $this->profileManager->selectList($this->userID);

        return $this->twig->render('Profiles/profilesList.html.twig', [
            'userId' => $this->userID,
            'list' => $profileList
        ]);
    }

    public function undisplayProfiles(array $undisplayProfiles, array $noPseudoProfiles, int $userID): string
    {
        $undisplayResult = [];
        foreach ($undisplayProfiles as $profile) {
            $undisplayResult[] = $profile['profile1Id'];
            $undisplayResult[] = $profile['profile2Id'];
        }
        foreach ($noPseudoProfiles as $profile) {
            $undisplayResult[] = $profile['id'];
        }
        $undisplayResult = array_unique($undisplayResult);
        $undisplayResult = implode(', ', $undisplayResult);

        // if undisplay profiles result research is empty:
        if ($undisplayResult == '') {
            $undisplayResult = $userID;
        }

        return $undisplayResult;
    }

    public function aggregatePictures(array $resultResearch): array
    {
        if (!empty($resultResearch)) {
            for ($i = count($resultResearch) - 1; $i > 0; $i--) {
                if ($resultResearch[$i]['id'] == $resultResearch[$i - 1]['id']) {
                    $resultResearch[$i - 1]['img_nom'] .= ' ' . $resultResearch[$i]['img_nom'];
                    array_splice($resultResearch, $i, 1);
                } else {
                    $resultResearch[$i]['img_nom'] = explode(" ", $resultResearch[$i]['img_nom']);
                }
            }
            $resultResearch[0]['img_nom'] = explode(" ", $resultResearch[0]['img_nom']);
        }

        return $resultResearch;
    }

    public function distanceCalculate(array $resultResearch, string $userTown): array
    {
        foreach ($resultResearch as $key => $profile) {
            $profileTown = $profile['town'];
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $userTown . "&destinations="
                . $profileTown . "&language=fr-FR&key=" . API_KEY;
            $raw = file_get_contents($url);
            $json = json_decode($raw, true);
            $rows = $json['rows'];

            if (!isset($rows[0])) {
                $distance = 'distance non calculée';
            } else {
                $elements = $rows[0]['elements'];
                $status = $elements[0]['status'];
                if ($status == 'NOT_FOUND') {
                    $distance = 'distance non calculée';
                } else {
                    $distance = $elements[0]['distance']['text'];
                }
            }
            $resultResearch[$key]['distance'] = $distance;
        }

        return $resultResearch;
    }
}
