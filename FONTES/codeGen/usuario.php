<?php
include_once '../config.php';
include_once '../templateClass.php';

$objDb  = new database();
$objFrm = new form();

$objInputIdUsuario = new textbox();
$objInputIdUsuario->strName = "id_usuario";
$objInputIdUsuario->strLabel = "id_usuario";
$objInputIdUsuario->strId = "id_usuario";

$objInputIdUsuario->strDisabled = "disabled";

$objComboIdPerfil = new combo($objDb->executaSP("select * from perfil_usuario"));
$objComboIdPerfil->strName = "id_perfil";
$objComboIdPerfil->strLabel = "id_perfil";
$objComboIdPerfil->blnRequiredField = "true";
$objComboIdPerfil->strId = "id_perfil";
$objComboIdPerfil->intDisplayField = 1;

$objInputNomUsuario = new textbox();
$objInputNomUsuario->strName = "nom_usuario";
$objInputNomUsuario->strLabel = "nom_usuario";
$objInputNomUsuario->strId = "nom_usuario";
$objInputNomUsuario->strClass = "span10";
$objInputNomUsuario->blnRequiredField = "true";

$objInputNomUsuario->strMaxLength = "100";

$objInputPwdUsuario = new textbox();
$objInputPwdUsuario->strName = "pwd_usuario";
$objInputPwdUsuario->strLabel = "pwd_usuario";
$objInputPwdUsuario->strId = "pwd_usuario";
$objInputPwdUsuario->strClass = "span2";
$objInputPwdUsuario->blnRequiredField = "true";

$objInputPwdUsuario->strMaxLength = "8";

$objInputDscEmail = new textbox();
$objInputDscEmail->strName = "dsc_email";
$objInputDscEmail->strLabel = "dsc_email";
$objInputDscEmail->strId = "dsc_email";
$objInputDscEmail->strClass = "span10";
$objInputDscEmail->strMaxLength = "50";

$objTpl = new template();
$objTpl->setFile('usuario');

$objTpl->setVars(array('css_dir'=>'css/',
'js_dir'=>'js/',
'img_dir'=>'img/',
'frmIni'=>$objFrm->renderizaFormIni(),
'frmFim'=>$objFrm->renderizaFormFim(),
'IdUsuario'=>$objInputIdUsuario->renderizaTextbox(),
'IdPerfil'=>$objComboIdPerfil->renderizaCombo(),
'NomUsuario'=>$objInputNomUsuario->renderizaTextbox(),
'PwdUsuario'=>$objInputPwdUsuario->renderizaTextbox(),
'DscEmail'=>$objInputDscEmail->renderizaTextbox()));

echo $objTpl->renderiza();
?>