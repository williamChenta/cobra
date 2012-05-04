<?php
/**  GERADOR DE TELAS
  *  WILLIAM CHENTA
  *  23/04/2012
  **/
  include_once './config.php';
  include_once './templateClass.php';

  $arrFields        = array();
  $strControlReturn = "";
  $strViewReturn    = "";
  $strTable         = $_POST['table'];
  $strTable         = 'usuario';

  /** INSTANCIA CLASSES DE ACESSO A BANCO E TEMPLATE */
  $objDtb           = new database();

  /** INICIA A GERAÇÃO DO ARQUIVO PHP */
  $fPHP     = fopen($config['codeDir'].$strTable.".php", 'w');
  $fHTML    = fopen($config['codeDir'].$config['htmlDir'].$strTable.".html", 'w');
  $fJSCRIPT = fopen($config['codeDir'].$config['jsDir'].$strTable.".js", 'w');

  $strJsReturn  = "$(document).ready(function(){\n";
  $strJsReturn .= "\t$('#btnSalvar').click(function(){ \n";

  fwrite($fPHP, "<?php\n");
  fwrite($fPHP, "include_once '../config.php';\n");
  fwrite($fPHP, "include_once '../templateClass.php';\n\n");

  $strViewReturn      = file_get_contents($config['codeDir'].$config['htmlDir']."header.html");
  $strViewReturn     .= "\n{frmIni}\n\n";

  $strControlReturn .= "$" . "objDb  = new database();\n";
  $strControlReturn .= "$" . "objFrm = new form();\n\n";

  /** RECUPERA OS CAMPOS E SEUS TIPOS DA TABELA */
  $rstResult  = $objDtb->executaSP("DESC $strTable");

  while ($row = $rstResult->fetch_row()) {

    /** SETA AS VARIAVEIS PHP NA VIEW */
    $strNomField       = ucwords(str_replace("_", " ", $row[0]));
    $strNomField       = str_replace(" ", "", $strNomField);
    $strViewReturn    .= "{". "$strNomField}\n";

    /** CHECA SE POSSUI PK OU FK */
    if (!empty($row[3])) {
      $objSchema = new schemaInfo($strTable, $row[0]);
      $rstSchema = $objSchema->fkInfo();
      $arrSchema = $rstSchema->fetch_row();

      /** SE POSSUI FOREIGN KEY, GERA UMA COMBO. SENÃO GERA TEXTBOX */
      if ($arrSchema){
        $strControlReturn .= "$"."objCombo$strNomField = new combo("."$"."objDb->executaSP(\"select * from $arrSchema[0]\"));\n";
        $strControlReturn .= "$"."objCombo$strNomField"."->strName = \"$arrSchema[1]\";\n";
        $strControlReturn .= "$"."objCombo$strNomField"."->strLabel = \"$arrSchema[1]\";\n";

        /** SETA CAMPO FOR OBRIGATÓRIO, SETA A VARIAVEL */
        if (strtoupper($row[2]) == 'NO') {
          $strControlReturn .= "$"."objCombo$strNomField"."->blnRequiredField = \"true\";\n";
          $strJsReturn .= "\t$(\"#frmCadastro\").setaObrigatorio('$arrSchema[1]');\n";
        }

        $strControlReturn .= "$"."objCombo$strNomField"."->strId = \"$arrSchema[1]\";\n\n";
        array_push($arrFields, $strNomField.'2');
      }
      else {
        $strControlReturn .= "$"."objInput$strNomField = new textbox();\n";
        $strControlReturn .= "$"."objInput$strNomField"."->strName = \"$row[0]\";\n";
        $strControlReturn .= "$"."objInput$strNomField"."->strLabel = \"$row[0]\";\n";
        $strControlReturn .= "$"."objInput$strNomField"."->strId = \"$row[0]\";\n\n";
        /** SE FOR AUTOINCREMENT, DEIXA DESABILITADO */
        if (strtoupper($row[5])=='AUTO_INCREMENT') {
          $strControlReturn .= "$"."objInput$strNomField"."->strDisabled = \"disabled\";\n\n";
        }

        /** É OBRIGATÓRIO E NÃO É AUTOINCREMENT */
        if (strtoupper($row[5])!='AUTO_INCREMENT' && strtoupper($row[2]) == 'NO') {
          $strControlReturn .= "$"."objInput$strNomField"."->blnRequiredField = \"true\";\n";
          $strJsReturn .= "\t$(\"#frmCadastro\").setaObrigatorio('$row[0]');\n";
        }

        array_push($arrFields, $strNomField.'1');
      }

      $objSchema->__destruct();
      unset($rstSchema);
    }
    else {
      $objColmnInfo      = new columnInfo();
      $objColmnInfo->getColumnInfo($row[1]);

      if (strtoupper($objColmnInfo->strFieldType) == 'TEXT'){
        $strControlReturn .= "$"."objTxtArea$strNomField = new textarea();\n";
        $strControlReturn .= "$"."objTxtArea$strNomField"."->strName = \"$row[0]\";\n";
        $strControlReturn .= "$"."objTxtArea$strNomField"."->strLabel = \"$row[0]\";\n";

        /** SETA CAMPO FOR OBRIGATÓRIO, SETA A VARIAVEL */
        if (strtoupper($row[2]) == 'NO') {
          $strControlReturn .= "$"."objTxtArea$strNomField"."->blnRequiredField = \"true\";\n\n";
          $strJsReturn .= "\t$(\"#frmCadastro\").setaObrigatorio('$row[0]');\n";
        }

        $strControlReturn .= "$"."objTxtArea$strNomField"."->strId = \"$row[0]\";\n\n";
        array_push($arrFields, $strNomField.'3');
      }
      else {
        $strControlReturn .= "$"."objInput$strNomField = new textbox();\n";
        $strControlReturn .= "$"."objInput$strNomField"."->strName = \"$row[0]\";\n";
        $strControlReturn .= "$"."objInput$strNomField"."->strLabel = \"$row[0]\";\n";
        $strControlReturn .= "$"."objInput$strNomField"."->strId = \"$row[0]\";\n";
        $strControlReturn .= "$"."objInput$strNomField"."->strClass = \"$objColmnInfo->strFieldClass\";\n";

        /** SETA CAMPO FOR OBRIGATÓRIO, SETA A VARIAVEL */
        if (strtoupper($row[2]) == 'NO') {
          $strControlReturn .= "$"."objInput$strNomField"."->blnRequiredField = \"true\";\n\n";
          $strJsReturn .= "\t$(\"#frmCadastro\").setaObrigatorio('$row[0]');\n";
        }

        $strControlReturn .= "$"."objInput$strNomField"."->strMaxLength = \"$objColmnInfo->strMaxLength\";\n\n";
        array_push($arrFields, $strNomField.'1');
      }

      $objColmnInfo->__destruct();
    }
  }

  $strControlReturn .= "$" . "objTpl = new template();\n";
  $strControlReturn .= "$" . "objTpl->setFile('$strTable');\n\n";
  $strControlReturn .= "$" . "objTpl->setVars(array('css_dir'=>'".$config['cssDir']."',\n'js_dir'=>'".$config['jsDir']."',\n'img_dir'=>'".$config['imgDir']."',\n";
  $strControlReturn .= "'frmIni'=>"."$"."objFrm->renderizaFormIni(),\n'frmFim'=>"."$"."objFrm->renderizaFormFim(),\n";

  foreach ($arrFields as $value) {
    $strOper  = substr($value, strlen($value)-1);
    $strValue = substr($value, 0, strlen($value)-1);

    switch ($strOper) {
      case '1':
        $strControlReturn .= "'$strValue'=>$" . "objInput$strValue"."->renderizaTextbox(),\n";
        break;
      case '2':
        $strControlReturn .= "'$strValue'=>$" . "objCombo$strValue"."->renderizaCombo(),\n";
        break;
      case '3':
        $strControlReturn .= "'$strValue'=>$" . "objTxtArea$strValue"."->renderizaTextarea(),\n";
        break;
    }
  }

  $strControlReturn  = substr($strControlReturn, 0, strlen($strControlReturn)-3) . ")));\n\n";
  $strControlReturn .= "echo $" . "objTpl->renderiza();\n";

  $strViewReturn    .= $strSchemaReturn . "\n{frmFim}\n" . file_get_contents("./".$config['codeDir'].$config['htmlDir']."footer.html");

  fwrite($fPHP, $strControlReturn);
  fwrite($fPHP, "?>");
  fclose($fPHP);

  fwrite($fHTML, $strViewReturn);
  fclose($fHTML);

  $strJsReturn .= "\t\tif($(\"#frmCadastro\").verificaObrigatorio()) { alert('pode salvar!'); } ";
  $strJsReturn .= "\t}); \n});";

  fwrite($fJSCRIPT, $strJsReturn);
  fclose($fJSCRIPT);

  /** INCLUI ARQUIVOS JS DINAMICAMENTE NA VIEW GERADA */
  includeFiles::includeJsFile($config['codeDir'].$config['htmlDir'].$strTable.".html", $strTable, 11);

  echo 'gerou!!';

?>