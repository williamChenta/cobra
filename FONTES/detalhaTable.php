<?php
 /** LISTA TABELAS DO DATABASE SELECIONADO
  *  WILLIAM CHENTA
  *  12/04/2012
  **/
  include_once 'config.php';
  include_once 'templateClass.php';

  $objTpl                   = new template();
  $objDb                    = new database();

  $strNomeTable             = $_POST['nomeTable'];
  $strNomeTable             = 'despesa';

  $strDatabase              = $objDb->retDatabase();
  $rstResult                = $objDb->executaSP("DESC $strNomeTable;");
  $objGrid                  = new grid($rstResult);
  $htmlGrid                 = $objGrid->renderizaGrid();

  $rstResult                = $objDb->executaSP("SHOW CREATE TABLE $strNomeTable;");
  $arrComando               = $rstResult->fetch_row();

  $objCombo                 = new combo($objDb->executaSP("select * from tipo_pagamento;"));
  $objCombo->strId          = 'cmbTipoPgto1';
  $objCombo->strName        = 'cmbTipoPgtoName';
  $objCombo->strDefaultVal  = '2';
  $objCombo->strChangeEvent = 'alert(\'alterou!!\')';

  $objText = new textbox();
  $objText->strValue = 'valor de teste';
  $objText->strLabel = 'Digite seu nome:';
  $objText->strLabelStyle = 'color:red; " width:200px; text-align:left';
  $objText->strFieldStyle = 'width:500px';
  $objText->strId    = 'Text1:';
  $objText->strMaxLength = 3;

  $objTextArea = new textarea();
  $objTextArea->strLabel    = 'teste de textbox';
  $objTextArea->strNumRows  = '6';
  $objTextArea->strClass    = 'xxlarge';
  $objTextArea->strValue    = 'william chenta';
  $objTextArea->strChangeEvent   = 'alert(\'alterou!!!\')';

  $objCheck = new checkOrRadio(array('1'=>'checkbox1','2'=>'checkbox2','3'=>'checkbox3'));
  $objCheck->strName  = 'sexo';
  $objCheck->strType  = 'checkbox';



  $objTpl->setFile('detalhaTable');
  $objTpl->setVars(array( 'css_dir'       =>'./css',
                          'js_dir'        =>'./js',
                          'img_dir'       =>'./img',
                          'database'      =>$strDatabase,
                          'detalhaTable'  =>$htmlGrid,
                          'combo'         =>$objCombo->renderizaCombo(),
                          'textbox'       =>$objText->renderizaTextbox(),
                          'textarea'      =>$objTextArea->renderizaTextarea(),
                          'checkbox'      =>$objCheck->renderizaCheckOrRadio(),
                          'comando'       => $arrComando[1]));

  echo $objTpl->renderiza();

?>