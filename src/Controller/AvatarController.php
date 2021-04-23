<?php

namespace App\Controller;

use App\Model\ConnectManager;

class AvatarController extends AbstractController
{
    public function avatarCreate()
    {
        // On crécupère le userId pour identification
        $_SESSION ['userId'] = 3;
        $string = $_SESSION ['userId'];
        // On créé le hash à partir d'une chaîne de caractères aléatoire
        $randomString = random_bytes(6);
        $hash = md5($randomString);
// On récupère une couleur héxadécimale avec les 6 premiers caractères du hash
        $color = substr($hash, 0, 6);
// On déclare notre image et nos couleurs
        $image = imagecreate(50, 50);
//image 50 pixels de côté
        $color = imagecolorallocate(
            $image,
            hexdec(substr($color, 0, 2)),
            hexdec(substr($color, 2, 2)),
            hexdec(substr($color, 4, 2))
        );
        $backgd = imagecolorallocate($image, 255, 255, 255);

        // Pour chaque colonne :
            // Pour chaque ligne :
        for ($x = 0; $x < 5; $x++) {
            for ($y = 0; $y < 5; $y++) {
            // On récupère le caractère correspondant dans le hash
                // On décode sa valeur héxadécimale
                // Si le nombre est pair $pixel vaut true sinon $pixel vaut false
                $pixel = hexdec($hash[($x * 5) + $y]) % 2 == 0;
            // Par défaut le bloc est de même couleur que l'arrière-plan
                $pixelColor = $backgd;
            // Mais si $pixel vaut true alors on "allume" ce bloc en lui donnant la couleur de $color
                if ($pixel) {
                    $pixelColor = $color;
                }
                // On place chaque bloc de l'image
                imagefilledrectangle($image, $x * 10, $y * 10, ($x + 1) * 10, ($y + 1) * 10, $pixelColor);
            }
        }

        // On affiche l'image
        header('Content-type: image/png');
        $road = 'assets/images/avatars/avatar' . $string . '.png';
        imagepng($image, $road); //création fichier avatar
        header("location: /profile/index");
    }

    public function avatarTransfert()
    {
        $this->avatarCreate();
        $this->twig->render('Profiles/profile.html.twig');
    }
}
