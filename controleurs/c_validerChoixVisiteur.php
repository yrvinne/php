<?php
/**
 * Gestion de la validation des frais des visiteurs
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);  
$mois = getMois(date('d/m/Y'));
if (isset($_SESSION['idVisiteur'])) {
    $idVisiteur = $_SESSION['idVisiteur'];
}
switch ($action) {
case 'choixVisiteur':
    if (isset($_POST['visiteurNameSurname'])) {
        $str = $_POST['visiteurNameSurname'];
        $string = explode(" ", $str);
        $nom = $string[0];
        if (!empty($string[1])) {
            $prenom = $string[1];
            if (!empty($nom && $prenom)) {
                $_SESSION['idVisiteur'] = $pdo->getIdVisiteur($nom, $prenom);
                $idVisiteur = $_SESSION['idVisiteur'];
                $lesMois = $pdo->getLesMoisDisponibles($idVisiteur[0]);
                $lesCles = array_keys($lesMois);
                include 'vues/v_listeMois.php';
            }
        }
    }
    break;
case 'voirEtatFrais': 
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        $lesMois = $pdo->getLesMoisDisponibles($idVisiteur[0]);
        $moisASelectionner = $leMois;
        include 'vues/v_listeMois.php';
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur[0], $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur[0], $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur[0], $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        include 'vues/v_listeFraisForfait.php';
        include 'vues/v_listeFraisHorsForfait.php';
        include 'vues/v_etatFrais.php';
    break;
case 'validerMajFraisForfait':
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur[0], $mois, $lesFrais);
    } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
    }
    break;
case 'supprimerFrais':
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
        $pdo->supprimerFraisHorsForfait($idFrais);
    break;
case 'validerCreationFrais':
        $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
        $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
        valideInfosFrais($dateFrais, $libelle, $montant);
    if (nbErreurs() != 0) {
            include 'vues/v_erreurs.php';
    } else {
            $pdo->creeNouveauFraisHorsForfait(
                $idVisiteur[0],
                $mois,
                $libelle,
                $dateFrais,
                $montant
            );
    }
    break;
}
    require 'vues/v_validerFiche.php';