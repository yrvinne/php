<?php
/**
 * Vue Liste des visiteurs
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
?>

<?php $lesVisiteurs = $pdo->getVisiteur(); ?>

<script type="text/javascript" 
        src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js">
</script>

<div class="row">
    <div class="col-md-4">
        <h4>Choisir le visiteur : </h4>
        <form id="validerFrais" method="post" action="indexcompta.php?uc=validerChoixVisiteur&action=choixVisiteur">
        <input name="visiteurNameSurname" id="lstVisiteurs" type="text" list="liste" class="form-control"/>
        <datalist id="liste" name="lstVisiteurs" >
                            <?php
                            foreach ($lesVisiteurs as $unvisiteur) {
                                
                                $nom = $unvisiteur['nom'];
                                $prenom = $unvisiteur['prenom']; ?>
                                <option value="<?php echo $nom .' '. $prenom; ?>">
                                </option>
                                <?php 
                            }
                            ?>
            </datalist>
            </form>
            
           
               
             
           

<script>
$(document).ready(function(){
                $("#validerFrais").focusout(function(){
                    $("#validerFrais").submit();
                });
              });
</script>





