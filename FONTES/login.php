<?php

/**
 *  TELA DE LOGIN DO SISTEMA
 *  WILLIAM CHENTA
 *  05/04/2012
 */

include_once 'config.php';
include_once 'templateClass.php';

$tplObj = new template();

$tplObj->setFile('login');
$tplObj->setVars(array('css_dir'=>'./css', 'js_dir'=>'./js', 'img_dir'=>'./img'));

echo $tplObj->renderiza();

?>
