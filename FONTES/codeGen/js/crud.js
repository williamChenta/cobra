/**
  * @file JQUERY.OBRIGATORIO.JS
  * @brief PLUGIN RESPONSAVEL POR VERIFICAR SE OS CAMPOS ESTAO OU NAO COM DADOS
  * @author ANDRE ANTONIO LEMOS DE MORAES
  * @date 21/12/2011
  * @version 1.0
  */

(function($) {
  $.setaObrigatorio = function (strCampos) {
    var arrCampos = strCampos ?  strCampos.replace(/\s/g, "").split(",") : new Array();
    var arrTipos =  ['text','password','hidden','textarea','select-one','select-multiple','checkbox','radio'];
    $.each(arrCampos, function (intIndex, strIdCampo) {
      var objElemento = $("#"+strIdCampo)[0];
      if($.inArray(objElemento.type, arrTipos) !== -1) {
        $.data(objElemento, 'obrigatorio', true);
      }
    });
  };

  $.fn.removeTodosObrigatorios = function() {
    var arrTipos =  ['text','password','hidden','textarea','select-one','select-multiple','checkbox','radio'];
    if(this[0].nodeName == "FORM") {
      $(this).each(function(){
        $.each(this, function(intIndex, objElemento){
          if($.inArray(objElemento.type, arrTipos) !== -1) {
            $.removeData(objElemento, 'obrigatorio');
          }
        });
      });
    }
  };

  $.fn.setaObrigatorio = function(strCampos) {
    var arrCampos = strCampos ?  strCampos.replace(/\s/g, "").split(",") : new Array();
    var arrTipos =  ['text','password','hidden','textarea','select-one','select-multiple','checkbox','radio'];
    if(this[0].nodeName == "FORM") {
      $(this).each(function(){
        $.each(this, function(intIndex, objElemento){
          if($.inArray(objElemento.type, arrTipos) !== -1) {
            if($.inArray(objElemento.id, arrCampos) !== -1)
              $.data(objElemento, "obrigatorio", "true");
          }
        });
      });
    } else if($.inArray(this[0].nodeName, arrTipos !== -1)){
      this.data('obrigatorio', true);
    }
  }

  $.fn.removeObrigatorio = function(strCampos) {
    var arrCampos = strCampos ?  strCampos.replace(/\s/g, "").split(",") : new Array();
    var arrTipos =  ['text','password','hidden','textarea','select-one','select-multiple','checkbox','radio'];
    if(this[0].nodeName == "FORM") {
      $(this).each(function(){
        $.each(this, function(intIndex, objElemento){
          if($.inArray(objElemento.id, arrCampos) !== -1)
            $.data(objElemento, 'obrigatorio', false);
        });
      });
    } else if($.inArray(this[0].nodeName, arrTipos !== -1)){
      this.data('obrigatorio', false);
    }
  }

  $.fn.verificaObrigatorio = function(blnLista) {
    blnLista = blnLista ? blnLista : false;
    var blnVerificacao = false;
    var strLimpaCaracter = /[.:,]/g;
    var strFinal = "Os seguintes campos s\xe3o obrigat\xf3rios: ";
    var arrTipos =  ['text','password','hidden','textarea','select-one','select-multiple','checkbox','radio'];
    var intControlaContagem = 0;
    var objVerificaTipos = {
      'text' : function(objElemento) {
        var strTexto = null;
        if($.data(objElemento, 'obrigatorio')) {
          if($(objElemento).val() == null || $(objElemento).val() == "" || $(objElemento).val() == $(objElemento).attr('placeholder') ) {
            strTexto = $.trim($("label[for='"+objElemento.id+"']").text().replace(strLimpaCaracter, ""));
            if(!blnLista) {
              alert("Campo "+strTexto+" \xe9 obrigat\xf3rio");
              $(objElemento).focus();
            } else {
              strFinal += "\n" + strTexto;
            }
            return 1;
          } else {
            return 0;
          }
        } else {
          return 0;
        }
      },

      'selectOne' : function(objElemento) {
        var strTexto = null;
        if($.data(objElemento, 'obrigatorio') && objElemento.selectedIndex >= 0) {
          var intVal = $(objElemento).find(':selected').val();
          if(intVal == -1 || intVal == "" || intVal == 0) {
            strTexto = $.trim($("label[for='"+objElemento.id+"']").text().replace(strLimpaCaracter, ""));
            if(!blnLista) {
              alert("Campo "+strTexto+" \xe9 obrigat\xf3rio");
              $(objElemento).focus();
            } else {
              strFinal += "\n" + strTexto;
            }
            return 1;
          } else {
            return 0;
          }
        } else {
          return 0;
        }
      },

      'selectMultiple' : function (objElemento) {
        var strTexto = null;
        var intContador = 0;
        if($.data(objElemento, 'obrigatorio')) {
          $(objElemento).find('option:selected').each(function () {
            intContador++;
          });
          if(intContador == 0) {
            strTexto = $.trim($("label[for='"+objElemento.id+"']").text().replace(strLimpaCaracter, ""));
            if(!blnLista) {
              alert("Campo "+strTexto+" \xe9 obrigat\xf3rio");
              $(objElemento).focus();
            } else {
              strFinal += "\n" + strTexto;
            }
            return 1;
          } else {
            return 0;
          }
        } else {
          return 0;
        }
      },

      'checked' : function (objElemento) {
        var strTexto = null;
        var intContador = 0;
        if($.data(objElemento, 'obrigatorio')) {
          $("input[id='"+objElemento.id+"']").each(function() {
            if($.data(this, 'obrigatorio') && $(this).attr('checked') == 'checked') {
              intContador++;
            }
          });
          if(intContador == 0) {
            strTexto = $.trim($("label[for='"+objElemento.id+"']").text().replace(strLimpaCaracter, ""));
            if(!blnLista) {
              alert("Campo "+strTexto+" \xe9 obrigat\xf3rio");
              $(objElemento).focus();
            } else {
              strFinal += "\n" + strTexto;
            }
            return 1;
          } else {
            return 0;
          }
        } else {
          return 0;
        }
      }
    };

    var pegaTipo = function (arrTodos, strTipoSelecionado) {
      var arrRetorno = ['text', 'selectOne', 'selectMultiple', 'checked'];
      if($.inArray(strTipoSelecionado, arrTodos) >= 0 && $.inArray(strTipoSelecionado, arrTodos) <= 3 ) {
        return arrRetorno[0];
      } else if($.inArray(strTipoSelecionado, arrTodos) == 4  ) {
        return arrRetorno[1];
      } else if($.inArray(strTipoSelecionado, arrTodos) == 5  ) {
        return arrRetorno[2];
      } else if($.inArray(strTipoSelecionado, arrTodos) == 6 || $.inArray(strTipoSelecionado, arrTodos) == 7 ) {
        return arrRetorno[3];
      }
    };

    if(this[0].nodeName == "FORM") {
      $(this).each(function(){
        $.each(this, function(intIndex, objElemento){
          if(intControlaContagem == 0 ) {
            if($.inArray(objElemento.type, arrTipos) !== -1) {
              var strTipo = pegaTipo(arrTipos, objElemento.type);
              if(objVerificaTipos[strTipo]) {
                intControlaContagem += objVerificaTipos[strTipo](objElemento);
              }
            }
          } else {
            if(blnLista) {
              if($.inArray(objElemento.type, arrTipos) !== -1) {
                var strTipo = pegaTipo(arrTipos, objElemento.type);
                if(objVerificaTipos[strTipo]) {
                  intControlaContagem += objVerificaTipos[strTipo](objElemento);
                }
              }
            }
          }
        });
      });

      if(intControlaContagem > 0) {
        if(blnLista) {
          alert(strFinal);
        }
        return false;
      } else {
        return true;
      }
    } else if($.inArray(this[0].nodeName, arrTipos !== -1)){
      blnLista = false;
      var objElemento = this[0];
      var strTipo = pegaTipo(arrTipos, objElemento.type);
      intControlaContagem += objVerificaTipos[strTipo](objElemento);
      if(intControlaContagem > 0) {
        return false;
      } else {
        return true;
      }
    }
  };
}(jQuery))