<?php

/** ARQUIVO DE CONFIGURAÇÕES DO SISTEMA
 *  WILLIAM CHENTA
 *  30/03/2012
 * */
error_reporting(1);

class database {

  private $objCon;

  private function conectaDatabase() {
    global $config;
    $this->objCon = new mysqli($config['DatabaseServer'], $config['UsuDatabase'], $config['PwdDatabase'], $config['NomDatabase']);

    if ($this->objCon->connect_error) {
      die('Ocorreu um erro ao tentar conectar ao banco!<br>Verifique se os parâmetros de conexão (HOST/DATABASE/USUÁRIO/SENHA) estão corretos!');
    }
  }

  private function fechaConexao() {
    $this->objCon->close();
  }

  public function executaSP($strSP) {
    global $config;
    $this->conectaDatabase();

    $this->objCon->query("SET lc_messages = '" . $config['LanguageMessages'] . "';");
    $rstResult = $this->objCon->query($strSP);
    $this->fechaConexao();

    if ($rstResult) {
      return $rstResult;
    } else {
      if ($config['ErrorMessages']) {
        die($this->objCon->error);
      } else {
        die('Ocorreu um erro ao tentar processar a query! Verifique se a sintaxe esta correta!');
      }
    }
  }

  public function retDatabase() {
    global $config;
    return $config['NomDatabase'];
  }
}

class schemaInfo extends database {
  private $strTableName;
  private $strColumnName;
  private $strQuery;

  public function __construct($strTableName, $strColumnName) {
    $this->strTableName  = $strTableName;
    $this->strColumnName = $strColumnName;
  }

  public function fkInfo() {
    $this->strQuery      = " SELECT   REFERENCED_TABLE_NAME,
                                      REFERENCED_COLUMN_NAME
                              FROM    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                              WHERE   TABLE_NAME 	= '$this->strTableName'
                              AND     INFORMATION_SCHEMA.KEY_COLUMN_USAGE.CONSTRAINT_SCHEMA = '". parent::retDatabase() ."'
                              AND     COLUMN_NAME       = '$this->strColumnName'
                              AND     REFERENCED_TABLE_NAME  IS NOT NULL
                              AND     REFERENCED_COLUMN_NAME IS NOT NULL ";

    return $this->executaSP($this->strQuery);
  }

  public function __destruct(){}
}

class columnInfo {

  public $strMaxLength;
  public $strFieldType;
  public $strFieldClass;

  public function getColumnInfo($strColumn) {
    $arrReplace         = array('(',')');
    $this->strFieldType = str_replace($arrReplace,'',substr($strColumn, 0, strpos($strColumn, "(")));
    $this->strMaxLength = str_replace($arrReplace,'',substr($strColumn, strpos($strColumn, "("), strpos($strColumn, ")")));

    switch (true){
      case ($this->strMaxLength <= 10):
        $this->strFieldClass = 'span2';
        break;
      case ($this->strMaxLength > 10 && $this->strMaxLength <= 20):
        $this->strFieldClass = 'span4';
        break;
      case ($this->strMaxLength > 20 && $this->strMaxLength <= 40):
        $this->strFieldClass = 'span6';
        break;
      case ($this->strMaxLength > 40):
        $this->strFieldClass = 'span10';
        break;
    }

    if (empty($this->strFieldType)){
      $this->strFieldType = $strColumn;
    }
  }

  public function __destruct(){}
}

/** VARIAVEIS DE BANCO */
$config['DatabaseServer']   = 'localhost';
$config['NomDatabase']      = 'despesasPessoais';
$config['UsuDatabase']      = 'root';
$config['PwdDatabase']      = 'root';
$config['ErrorMessages']    = true;
$config['LanguageMessages'] = 'pt_BR';

/** VARIAVEIS DE PATH */
$config['codeDir'] = './codeGen/';
$config['cssDir']  = 'css/';
$config['htmlDir'] = 'html/';
$config['imgDir']  = 'img/';
$config['jsDir']   = 'js/';

?>