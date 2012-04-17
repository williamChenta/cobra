<?php
 /** CLASSE DE TEMPLATE DO SISTEMA
  *  WILLIAM CHENTA
  *  05/04/2012
  **/

  include_once './config.php';

  class template extends config {
    private $file    = '';
    private $vars    = array();

    public function setFile($strFile) {
      $this->file = $strFile;
    }

    public function setVars($arrVars) {
      $this->vars = $arrVars;
    }

    public function renderiza(){
      $fileContents = file_get_contents( parent::__get('htmlDir') . $this->file . '.html');
      foreach ($this->vars as $key => $value) {
        $fileContents = str_replace("{".$key."}", $value, $fileContents);
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
    private $rstResultSet  = 0;
    private $intNumFields  = 0;
    private $strHtmlReturn = '';
    public  $strClickEvent;
    public  $arrClickParams;

    public function __construct($rstFields) {
      $this->rstResultSet   = $rstFields;
      $this->intNumFields   = $this->rstResultSet->field_count;
    }

    public function renderizaGrid(){
      while ($row = $this->rstResultSet->fetch_row()) {

        /** SETA EVENTO ONCLICK */
        if (!empty($this->strClickEvent)) {
          $this->strHtmlReturn .= "<tr onclick='$this->strClickEvent(";

          foreach ($this->arrClickParams as $intIndice) {
            $this->strHtmlReturn .= "\"$row[$intIndice]\",";
          }
          $this->strHtmlReturn  = substr($this->strHtmlReturn, 0, strlen($this->strHtmlReturn)-1);
          $this->strHtmlReturn .= ");'>";
        }
        else {
          $this->strHtmlReturn .= "<tr>";
        }

        for($i=0;$i<$this->intNumFields;$i++){
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
  class combo {
    private $rstResultSet  = 0;
    private $strHtmlReturn = '';
    public  $strId;
    public  $strName;
    public  $strLabel;
    public  $strLabelStyle;
    public  $strFieldStyle;
    public  $strClass;
    public  $strChangeEvent;
    public  $strDisabled    = '';
    public  $strMultiple    = '';
    public  $blnEmptyVal    = true;
    public  $strDefaultVal  = '';

    public function __construct($rstFields) {
      $this->rstResultSet   = $rstFields;
    }

    public function renderizaCombo(){

      $this->strLabelStyle  = !empty($this->strLabelStyle) ? "style=\"$this->strLabelStyle\"" : "";
      $this->strFieldStyle  = !empty($this->strFieldStyle) ? "style=\"$this->strFieldStyle\"" : "";

      $this->strLabel       = !empty($this->strLabel) ? $this->strLabel : $this->strId . ":";
      $this->strHtmlReturn  = "<div class=\"clearfix\">";
      $this->strHtmlReturn .= "<label for=\"$this->strId\" $this->strLabelStyle>$this->strLabel&nbsp;</label>";

      $this->strHtmlReturn .= "<div class=\"input\"><select id=\"$this->strId\" name=\"$this->strName\" onchange=\"$this->strChangeEvent\" class=\"$this->strClass\" $this->strDisabled $this->strMultiple $this->strFieldStyle >";

      if ($this->blnEmptyVal){
        $this->strHtmlReturn .= "<option value=\"\"></option>";
      }

      if(!is_array($this->rstResultSet)) {
        while ($row = $this->rstResultSet->fetch_row()) {
          $strDefault           = ($this->strDefaultVal == $row[0]) ? "selected" : "";
          $row[1]               = isset($row[1]) && !empty($row[1]) ? $row[1] : $row[0];
          $this->strHtmlReturn .= "<option value=\"$row[0]\" $strDefault>$row[1]</option>";
        }
      }
      else {
        foreach ($this->rstResultSet as $key => $value) {
          $strDefault           = ($this->strDefaultVal == $key) ? "selected" : "";
          $this->strHtmlReturn .= "<option value=\"$key\" $strDefault>$value</option>";
        }
      }

      $this->strHtmlReturn .= "</div></select></div>";
      return $this->strHtmlReturn;
    }
  }

  /** CLASSE PARA CONSTRUÇÃO DE TEXTBOXES.
   *  WILLIAM CHENTA
   *  16/04/2012
   */
  class textbox {
    private $strHtmlReturn = '';
    public  $strId;
    public  $strName;
    public  $strLabel;
    public  $strLabelStyle;
    public  $strFieldStyle;
    public  $strValue;
    public  $strClass;
    public  $strChangeEvent;
    public  $strDisabled  = '';
    public  $strReadonly  = '';
    public  $strSize      = '10';
    public  $strMaxLength = '';
    public  $strAlt       = '';

    public function renderizaTextbox(){

      $this->strLabelStyle  = !empty($this->strLabelStyle) ? "style=\"$this->strLabelStyle\"" : "";
      $this->strFieldStyle  = !empty($this->strFieldStyle) ? "style=\"$this->strFieldStyle\"" : "";
      $this->strLabel       = !empty($this->strLabel) ? $this->strLabel : $this->strId . ":";
      $this->strHtmlReturn  = "<div class=\"clearfix\">";
      $this->strHtmlReturn .= "<label for=\"$this->strId\" $this->strLabelStyle>$this->strLabel&nbsp;</label>";
      $this->strHtmlReturn .= "<div class=\"input\"><input type=\"text\" size=\"$this->strSize\" maxlength=\"$this->strMaxLength\" name=\"$this->strName\" id=\"$this->strId\" class=\"$this->strClass\" onchange=\"$this->strChangeEvent\" alt=\"$this->strAlt\" value=\"$this->strValue\" $this->strDisabled $this->strReadonly $this->strFieldStyle></div>";
      $this->strHtmlReturn .= "</div>";

      return $this->strHtmlReturn;
    }
  }

  class textarea {
    private $strHtmlReturn = '';
    public  $strId;
    public  $strName;
    public  $strLabel;
    public  $strLabelStyle;
    public  $strFieldStyle;
    public  $strValue;
    public  $strClass;
    public  $strChangeEvent;
    public  $strDisabled  = '';
    public  $strReadonly  = '';
    public  $strNumRows   = '';

    public function renderizaTextarea(){

      $this->strLabelStyle  = !empty($this->strLabelStyle) ? "style=\"$this->strLabelStyle\"" : "";
      $this->strFieldStyle  = !empty($this->strFieldStyle) ? "style=\"$this->strFieldStyle\"" : "";
      $this->strLabel       = !empty($this->strLabel) ? $this->strLabel : $this->strId . ":";

      $this->strHtmlReturn  = "<div class=\"clearfix\">";
      $this->strHtmlReturn .= "<label for=\"$this->strId\" $this->strLabelStyle>$this->strLabel&nbsp;</label>";
      $this->strHtmlReturn .= "<textarea rows=\"$this->strNumRows\" name=\"$this->strName\" id=\"$this->strId\" class=\"$this->strClass\" style=\"$this->strFieldStyle\" class=\"$this->strClass\" onchange=\"$this->strChangeEvent\" alt=\"$this->strAlt\" $this->strDisabled>$this->strValue</textarea></div></div>";
      return $this->strHtmlReturn;
    }
  }

  class checkOrRadio {
    private $rstResultSet  = 0;
    public  $strType       = 'radio';
    private $strHtmlReturn = '';
    public  $strName;
    public  $strLabel;
    public  $strLabelStyle;
    public  $strClickEvent;
    public  $strDisabled  = '';

    public function __construct($rstFields) {
      $this->rstResultSet  = $rstFields;
    }

    public function renderizaCheckOrRadio(){

      $this->strType = ($this->strType == 'radio' || $this->strType == 'checkbox') ? $this->strType : 'radio';

      $this->strHtmlReturn  = "<fieldset><div class=\"clearfix\">";
      $this->strHtmlReturn .= "<label id=\"$this->strName\">List of options</label>";
      $this->strHtmlReturn .= "<div class=\"input\" style=\"margin-left:40px\">";
      $this->strHtmlReturn .= "<ul class=\"inputs-list\">";

      if(!is_array($this->rstResultSet)) {
        while ($row = $this->rstResultSet->fetch_row()) {
          $row[1]               = isset($row[1]) && !empty($row[1]) ? $row[1] : $row[0];
          $this->strHtmlReturn .= "<li><label><input type=\"$this->strType\" value=\"opt$row[0]\" name=\"$this->strName\"><span>$row[1]</span></label></li>";
        }
      }
      else {
        foreach ($this->rstResultSet as $key => $value) {
          $this->strHtmlReturn .= "<li><label><input type=\"$this->strType\" value=\"opt$key\" name=\"$this->strName\"><span>$value</span></label></li>";
        }
      }
      $this->strHtmlReturn .= "</ul></div></div></fieldset>";
      return $this->strHtmlReturn;
    }
  }

?>