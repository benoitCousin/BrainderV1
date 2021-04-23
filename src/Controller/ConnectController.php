<?php

namespace App\Controller;

use App\Model\ConnectManager;

/*explique présence ConnectMAnager*/
/*session_start();*/


class ConnectController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('Home/index.html.twig');
    }

    public function verify()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) { /*vérification remplissage champs*/
            $data = array_map('trim', $_POST); /*tri données pour éliminer le superflu*/
            $email = htmlentities($data['email']); /*html entities à vérifier*/
            $password = htmlentities($data['password']);

            $connectManager = new ConnectManager();
            $identity = $connectManager->selectOneByMail($email);  /*renvoie le passwords*/

            if ($identity == false) {/*vérification mail*/
                    return $this->twig->render('Home/errorLogin.html.twig');
            } else {
                if ($identity['pswd'] == $password) { /*comparaison des passwords*/
                    $_SESSION['userId'] = $identity['id']; /*création identité par ouverture session*/

                    return $this->twig->render('Profiles/profilesList.html.twig'); /* page liste profils*/
                } else {/*else renvoi sur page acceuil*/
                    return $this->twig->render('Home/errorLogin2.html.twig');
                }
            }
        }
    }
}
