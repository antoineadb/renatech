<?php
session_start();
include_once 'class/Manager.php';
include_once	'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include('decide-lang.php');
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
        check_authent($_SESSION['pseudo']);
    } else {
        header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    }
include 'html/header.html';
?>

<div id="global">
    <?php include 'html/entete.html'; ?>
     <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>
    <div style="margin-top:40px;width:1050px" >       
                <fieldset id="new" style="border-color: #5D8BA2;width: 1008px;padding-bottom:30px;padding-top:10px;font-size:1.2em" >
                <legend><?php echo TXT_MANUAL; ?></legend>
                <table>
                    <tr>
                        <td>
                            <a href="<?php echo '/'.REPERTOIRE.'/downloadManual/Manuel_partie_commune.pdf' ?>" download="Manuel_partie_commune">
                                <img src="<?php echo '/'.REPERTOIRE; ?>/styles/img/pdf_icon.png"  >
                            </a>
                        </td>
                        <td><div style="margin-left:10px;margin-right: 100px"  title="<?php echo TXT_AIDEDOWNLOAD;?>"><?php echo TXT_MANUELCOMMUN;?></div></td>                    
                        <td>
                            <a href="<?php echo '/'.REPERTOIRE.'/downloadManual/Manuel_utilisateur.pdf' ?>" download="Manuel_utilisateur">
                                <img src="<?php echo '/'.REPERTOIRE; ?>/styles/img/pdf_icon.png"  >
                            </a>
                        </td>
                        <td><div style="margin-left:10px"  title="<?php echo TXT_AIDEDOWNLOAD;?>"><?php echo TXT_MANUEL;?></div></td>
                    </tr>
                    <?php if($_SESSION['idTypeUser']==ADMINLOCAL || $_SESSION['idTypeUser']==ADMINNATIONNAL){?>
                    <tr>
                        <td>
                            <a href="<?php echo '/'.REPERTOIRE.'/downloadManual/Manuel_administrateur_local.pdf' ?>" download="Manuel_admin_local">
                                <img src="<?php echo '/'.REPERTOIRE; ?>/styles/img/pdf_icon.png"  >
                            </a>
                        </td>
                        <td><div style="margin-left:10px;margin-right: 100px"  title="<?php echo TXT_AIDEDOWNLOAD;?>"><?php echo TXT_MANUELLOCAL;?></div></td>                    
                        <td>
                            <a href="<?php echo '/'.REPERTOIRE.'/downloadManual/Manuel_administrateur_national.pdf' ?>" download="Manuel_admin_nationnal">
                                <img src="<?php echo '/'.REPERTOIRE; ?>/styles/img/pdf_icon.png"  >
                            </a>
                        </td>
                        <td><div style="margin-left:10px"  title="<?php echo TXT_AIDEDOWNLOAD;?>"><?php echo TXT_MANUELNATIONAL;?></div></td>
                    </tr>
                    <?php } ?>
                </table>
                <?php if (isset($_GET['msgerr'])) { ?>
                <table id="msgerreur">
                        <tr>
                            <td>
                                <div  style=" color: #FF0000;font-size: 1em;padding-top: 4px;text-align: center;width: 1004px;" ><?php echo TXT_TYPENONSELECTIONNE.'!'; ?></div>
                            </td>
                        </tr>
                    </table>
                <?php } ?>
            </fieldset>
            <?php include 'html/footer.html'; ?>       
    </div>
</div>

