<?php
 /** ARQUIVO DE CONFIGURAÇÕES DO SISTEMA
  *  WILLIAM CHENTA
  *  12/04/2012
  **/
  include_once 'config.php';
  include_once 'templateClass.php';

  $objDb     = new database();
  $objTpl    = new template();
  $rstResult = $objDb->executaSP('show databases;');

  $objGrid   = new grid($rstResult);
  $htmlGrid  = $objGrid->renderizaGrid();

  $objTpl->setFile('listaDatabases');
  $objTpl->setVars(array( 'css_dir'   =>'./css',
                          'js_dir'    =>'./js',
                          'img_dir'   =>'./img',
                          'databases' =>$htmlGrid));

  echo $objTpl->renderiza();

?>