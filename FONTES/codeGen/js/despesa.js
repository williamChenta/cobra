$(document).ready(function(){
	$('#btnSalvar').click(function(){ 
	$("#frmCadastro").setaObrigatorio('id_usuario');
	$("#frmCadastro").setaObrigatorio('id_tipo_pagamento');
	$("#frmCadastro").setaObrigatorio('nom_despesa');
	$("#frmCadastro").setaObrigatorio('val_despesa');
	$("#frmCadastro").setaObrigatorio('num_parcelas');
	$("#frmCadastro").setaObrigatorio('dat_despesa');
		if($("#frmCadastro").verificaObrigatorio()) { alert('pode salvar!'); } 	}); 
});