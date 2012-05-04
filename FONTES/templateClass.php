<?php

/** CLASSE DE TEMPLATE DO SISTEMA
 *  WILLIAM CHENTA
 *  05/04/2012
 * */
include_once './config.php';

class template {

  private $file = '';
  private $vars = array();

  public function setFile($strFile) {
    $this->file = $strFile;
  }

  public function setVars($arrVars) {
    $this->vars = $arrVars;
  }

  public function renderiza() {
    global $config;
    $fileContents = file_get_contents($config['htmlDir'] . $this->file . '.html');
    foreach ($this->vars as $key => $value) {
      $fileContents = str_replace("{" . $key . "}", $value, $fileContents);
    }
    return $fileContents;
  }
}

/** CLASSE PARA CONSTRUÇÃO DE GRIDS.
 *  PARA EXIBIÇÃO DE RESULTADOS DE QUERYS QUE RETORNEM MAIS DE UMA LINHA. TABELAS HTML
 *  RECEBE UM RECORDSET COMO PARÂMETRO
 *  WILLIAM CHENTA
 *  09/04/2012
 */
class grid {

  private $rstResultSet = 0;
  private $intNumFields = 0;
  private $strHtmlReturn = '';
  public $strClickEvent;
  public $arrClickParams;

  public function __construct($rstFields) {
    $this->rstResultSet = $rstFields;
    $this->intNumFields = $this->rstResultSet->field_count;
  }

  public function renderizaGrid() {
    while ($row = $this->rstResultSet->fetch_row()) {

      /** SETA EVENTO ONCLICK */
      if (!empty($this->strClickEvent)) {
        $this->strHtmlReturn .= "<tr onclick='$this->strClickEvent(";

        foreach ($this->arrClickParams as $intIndice) {
          $this->strHtmlReturn .= "\"$row[$intIndice]\",";
        }
        $this->strHtmlReturn = substr($this->strHtmlReturn, 0, strlen($this->strHtmlReturn) - 1);
        $this->strHtmlReturn .= ");'>";
      } else {
        $this->strHtmlReturn .= "<tr>";
      }

      for ($i = 0; $i < $this->intNumFields; $i++) {
        $this->strHtmlReturn .= "<td>$row[$i]</td>";
      }
      $this->strHtmlReturn .= "</tr>";
    }
    return $this->strHtmlReturn;
  }
}

/** CLASSE PARA CONSTRUÇÃO DE COMBOS.
 *  PARA EXIBIÇÃO DE RESULTADOS DE QUERYS OU ARRAYS QUE RETORNEM MAIS DE UMA LINHA.
 *  RECEBE UM RECORDSET COMO PARÂMETRO
 *  WILLIAM CHENTA
 *  09/04/2012
 */
class combo extends configTpl {

  private $rstResultSet = 0;
  private $strHtmlReturn = '';

  public function __construct($rstFields) {
    $this->rstResultSet = $rstFields;
  }

  public function renderizaCombo() {
    $this->strLabel = !empty($this->strLabel) ? $this->strLabel : $this->strId . ":";
    $this->strHtmlReturn = "<div class=\"clearfix\">";
    $this->strHtmlReturn .= "<label for=\"$this->strId\" style=\"$this->strLabelStyle\">$this->strLabel&nbsp;</label>";

    $this->strHtmlReturn .= "<div class=\"input\"><select id=\"$this->strId\" name=\"$this->strName\" onchange=\"$this->strChangeEvent\" class=\"$this->strClass\" $this->strDisabled $this->strMultiple style=\"$this->strFieldStyle\" >";

    if ($this->blnEmptyVal) {
      $this->strHtmlReturn .= "<option value=\"\"></option>";
    }

    if (!is_array($this->rstResultSet)) {
      while ($row = $this->rstResultSet->fetch_row()) {
        $strDefault = ($this->strDefaultVal == $row[0]) ? "selected" : "";
        $intDisplayField = $this->intDisplayField;
        $this->strHtmlReturn .= "<option value=\"$row[0]\" $strDefault>$row[$intDisplayField]</option>";
      }
    } else {
      foreach ($this->rstResultSet as $key => $value) {
        $strDefault = ($this->strDefaultVal == $key) ? "selected" : "";
        $this->strHtmlReturn .= "<option value=\"$key\" $strDefault>$value</option>";
      }
    }

    if (!$this->blnRequiredField) {
      $this->strMsgRequired = "";
    }

    $this->strHtmlReturn .= "</div></select>".$this->strMsgRequired."</div></div>";
    return $this->strHtmlReturn;
  }
}

/** CLASSE PARA CONSTRUÇÃO DE TEXTBOXES.
 *  WILLIAM CHENTA
 *  16/04/2012
 */
class textbox extends configTpl {

  private $strHtmlReturn = '';

  public function renderizaTextbox() {
    $this->strLabel = !empty($this->strLabel) ? $this->strLabel : $this->strId . ":";
    $this->strHtmlReturn = "<div class=\"clearfix\">";
    $this->strHtmlReturn .= "<label for=\"$this->strId\" style=\"$this->strLabelStyle\">$this->strLabel&nbsp;</label>";

    if (!$this->blnRequiredField) {


      $this->strMsgRequired = "";
    }

    $this->strHtmlReturn .= "<div class=\"input\"><input type=\"text\" maxlength=\"$this->strMaxLength\" name=\"$this->strName\" id=\"$this->strId\" class=\"$this->strClass\" onchange=\"$this->strChangeEvent\" alt=\"$this->strAlt\" value=\"$this->strValue\" $this->strDisabled $this->strReadonly style=\"$this->strFieldStyle\">".$this->strMsgRequired."</div>";
    $this->strHtmlReturn .= "</div>";

    return $this->strHtmlReturn;
  }
}

class textarea extends configTpl {

  private $strHtmlReturn = '';

  public function renderizaTextarea() {

    $this->strLabel = !empty($this->strLabel) ? $this->strLabel : $this->strId . ":";

    $this->strHtmlReturn = "<div class=\"clearfix\">";
    $this->strHtmlReturn .= "<label for=\"$this->strId\" style=\"$this->strLabelStyle\">$this->strLabel&nbsp;</label>";
    $this->strHtmlReturn .= "<div class=\"input\">";
    $this->strHtmlReturn .= "<textarea rows=\"$this->strNumRows\" name=\"$this->strName\" id=\"$this->strId\" class=\"$this->strClass\" style=\"$this->strFieldStyle\" class=\"$this->strClass\" onchange=\"$this->strChangeEvent\" alt=\"$this->strAlt\" $this->strDisabled>$this->strValue</textarea></div></div>";
    return $this->strHtmlReturn;
  }
}

class checkOrRadio extends configTpl {

  private $rstResultSet = 0;
  private $strHtmlReturn = '';

  public function __construct($rstFields) {
    $this->rstResultSet = $rstFields;
  }

  public function renderizaCheckOrRadio() {
    $this->strType = ($this->strType == 'radio' || $this->strType == 'checkbox') ? $this->strType : 'radio';
    $this->strLabel = !empty($this->strLabel) ? $this->strLabel . ":" : $this->strName . ":";
    $this->strHtmlReturn = "<fieldset><div class=\"clearfix\">";
    $this->strHtmlReturn .= "<label id=\"$this->strName\">$this->strLabel</label>";
    $this->strHtmlReturn .= "<div class=\"input\">";
    $this->strHtmlReturn .= "<ul class=\"inputs-list\">";

    if (!is_array($this->rstResultSet)) {
      while ($row = $this->rstResultSet->fetch_row()) {
        $row[1] = isset($row[1]) && !empty($row[1]) ? $row[1] : $row[0];
        $this->strHtmlReturn .= "<li><label><input type=\"$this->strType\" value=\"opt$row[0]\" name=\"$this->strName\"><span>$row[1]</span></label></li>";
      }
    } else {
      foreach ($this->rstResultSet as $key => $value) {
        $this->strHtmlReturn .= "<li><label><input type=\"$this->strType\" value=\"opt$key\" name=\"$this->strName\"><span>$value</span></label></li>";
      }
    }
    $this->strHtmlReturn .= "</ul></div></div></fieldset>";
    return $this->strHtmlReturn;
  }
}

class button extends configTpl {

  private $strHtmlReturn = '';

  public function renderizaButton() {
    $this->strHtmlReturn = "<input type=\"$this->strType\" id=\"$this->strId\" class=\"$this->strClass\" style=\"$this->strFieldStyle\" onclick=\"$this->strClickEvent\" value=\"$this->strValue\" $this->strDisabled>";
    return $this->strHtmlReturn;
  }
}

class inputFile extends configTpl {

  private $strHtmlReturn = '';

  public function renderizaInputFile() {
    $this->strLabel = !empty($this->strLabel) ? $this->strLabel . ":" : $this->strName . ":";
    $this->strHtmlReturn = "<div class=\"clearfix\">";
    $this->strHtmlReturn .= "<label for=\"$this->strId\" style=\"$this->strLabelStyle\">$this->strLabel</label>";
    $this->strHtmlReturn .= "<div class=\"input\">";
    $this->strHtmlReturn .= "<input type=\"file\" name=\"$this->strName\" id=\"$this->strId\" class=\"$this->strClass\" style=\"$this->strFieldStyle\" $this->strDisabled>";
    $this->strHtmlReturn .= "</div></div>";

    return $this->strHtmlReturn;
  }
}

class panel extends configTpl {

  private $strHtmlReturn = '';

  public function renderizaPanelIni() {

    $this->strClass = !empty($this->strClass) ? $this->strClass : "well";

    $this->strHtmlReturn = "<div class=\"$this->strClass\" style=\"$this->strFieldStyle\">";
    return $this->strHtmlReturn;
  }

  public function renderizaPanelFim() {
    $this->strHtmlReturn = "</div>";
    return $this->strHtmlReturn;
  }
}

class form extends configTpl {

  public $strAction;
  public $strMethod = 'POST';
  private $strHtmlReturn = '';

  public function renderizaFormIni() {
    $this->strId = !empty($this->strId) ? $this->strId : 'frmCadastro';
    $this->strName = !empty($this->strName) ? $this->strName : 'frmCadastro';

    $this->strHtmlReturn = "<form method=\"$this->strMethod\" id=\"$this->strId\" name=\"$this->strName\" action=\"$this->strAction\" class=\"$this->strClass\" style=\"$this->strFieldStyle\">";
    return $this->strHtmlReturn;
  }

  public function renderizaFormFim() {
    $this->strHtmlReturn  = "</div><div class=\"actions\">";
    $this->strHtmlReturn .= "<button id=\"btnSalvar\" class=\"btn primary\" type=\"button\">Salvar</button>&nbsp;<button id=\"btnCancelar\" class=\"btn\" type=\"button\">Cancelar</button>";
    $this->strHtmlReturn .= "</div>";
    $this->strHtmlReturn .= "</form>";
    return $this->strHtmlReturn;
  }
}

class configTpl {

  public $strId;
  public $strName;
  public $strLabel;
  public $strLabelStyle;
  public $strFieldStyle;
  public $strClass;
  public $strChangeEvent;
  public $strDisabled;
  public $strMultiple;
  public $blnEmptyVal = true;
  public $strDefaultVal;
  public $strValue;
  public $strReadonly;
  public $strSize = '10';
  public $strMaxLength;
  public $strAlt;
  public $strNumRows = '3';
  public $strType = 'radio';
  public $strClickEvent;
  public $intDisplayField  = 0;
  public $blnRequiredField = false;
  public $strMsgRequired   = "&nbsp;<span class=\"label important\">Campo obrigatório!</span>";

  public function _destruct() {
    foreach ($this as $key) {
      unset($this->$key);
    }
  }
}

class includeFiles extends configTpl {
  public static function includeJsFile($strJsFile, $strNameFile, $intNumLine){
    $lines = file( $strJsFile, FILE_IGNORE_NEW_LINES );
    $lines[$intNumLine] = "<script type=\"text/javascript\" src=\"{js_dir}$strNameFile.js\"></script>";
    file_put_contents( $strJsFile, implode( "\n", $lines ) );
  }
}

?>