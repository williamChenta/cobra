$(document).ready(function(){
	$('#btnSalvar').click(function(){ 
	$("#frmCadastro").setaObrigatorio('id_perfil');
	$("#frmCadastro").setaObrigatorio('nom_usuario');
	$("#frmCadastro").setaObrigatorio('pwd_usuario');
		if($("#frmCadastro").verificaObrigatorio()) { alert('pode salvar!'); } 	}); 
});