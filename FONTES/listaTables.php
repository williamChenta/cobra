<?php
 /** LISTA TABELAS DO DATABASE SELECIONADO
  *  WILLIAM CHENTA
  *  12/04/2012
  **/
  include_once 'config.php';
  include_once 'templateClass.php';

  $objTpl                   = new template();
  $objDb                    = new database();
  $strDatabase              = $objDb->retDatabase();
  $rstResult                = $objDb->executaSP("SHOW TABLES FROM $strDatabase;");
  $objGrid                  = new grid($rstResult);
  $objGrid->strClickEvent   = 'teste';
  $objGrid->arrClickParams  = array('0','1','2','3');
  $htmlGrid                 = $objGrid->renderizaGrid();

  $objTpl->setFile('listaTables');
  $objTpl->setVars(array( 'css_dir'   =>'./css',
                          'js_dir'    =>'./js',
                          'img_dir'   =>'./img',
                          'database'  =>$strDatabase,
                          'tables'    =>$htmlGrid));

  echo $objTpl->renderiza();

?>