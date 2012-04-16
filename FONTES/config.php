<?php

/** ARQUIVO DE CONFIGURAï¿½ï¿½ES DO SISTEMA
 *  WILLIAM CHENTA
 *  30/03/2012
 * */
error_reporting(1);

class database extends config {

  private $objCon;

  private function conectaDatabase() {
    $this->objCon = new mysqli(parent::__get('strNomDatabaseServer'), parent::__get('strNomUsuDatabase'), parent::__get('strPwdUsuDatabase'), parent::__get('strNomDatabase'));

    if ($this->objCon->connect_error) {
      die('Ocorreu um erro ao tentar conectar ao banco!<br>Verifique se os parâmetros de conexão (HOST/DATABASE/USUÁRIO/SENHA) estão corretos!');
    }
  }

  private function fechaConexao() {
    $this->objCon->close();
  }

  public function executaSP($strSP) {
    $this->conectaDatabase();

    $this->objCon->query("SET lc_messages = '" . parent::__get('strLanguageMessages') . "';");
    $rstResult = $this->objCon->query($strSP, MYSQLI_USE_RESULT);

    if ($rstResult) {
      return $rstResult;
    } else {
      if (parent::__get('blnErrorMessages')) {
        die($this->objCon->error);
      } else {
        die('Ocorreu um erro ao tentar processar a query! Verifique se a sintaxe esta correta!');
      }
    }
    $this->fechaConexao();
  }

  public function retDatabase() {
    return parent::__get('strNomDatabase');
  }

}

abstract class config {

  /** VARIAVEIS DE BANCO */
  private $strNomDatabaseServer = 'localhost';
  private $strNomDatabase = 'despesasPessoais';
  private $strNomUsuDatabase = 'root';
  private $strPwdUsuDatabase = 'root';

  /** VARIAVEIS DE PATH */
  private $htmlDir = './html/';

  /** CONFIGURAï¿½ï¿½ES DE MENSAGENS DE ERRO
   *
   *   $blnErrorMessages:    EXIBE MENSAGENS DE ERRO DIRETO DO BANCO.
   *                         PARA SER USADO NA ETAPA DE DESENVOLVIMENTO.
   *
   *   $strLanguageMessages: SETA LINGUAGEM UTILIZADA PELO BANCO PARA EXIBIR MENSAGENS DE ERRO.
   *                         en_US, pt_BR, fr_FR, etc...
   * */
  private $blnErrorMessages = true;
  private $strLanguageMessages = 'pt_BR';

  public function __get($name) {
    return $this->$name;
  }

}

?>