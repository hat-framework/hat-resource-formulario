<?php
/**
 * @author: Willian Luis Zuqui
 * EXEMPLO DE BUSCA DE CEP USANDO WEBSERVICE MUITO BOM DO www.buscacep.com.br
 * E TAMBEM jQUERY COM O PLUGIN MASKEDINPUT PARA NÃO FICAR MANDANDO CEP INCORRETO NO SERVICO
 * */
if(isset($_POST['cep'])){
	header("Content-Type:text/xml");
	echo file_get_contents('http://www.buscarcep.com.br/?cep='.$_POST['cep'].'&formato=xml');
	exit;
}
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" language="javascript" type="text/javascript"></script>
	<script src="http://digitalbush.com/files/jquery/maskedinput/1.1.4/jquery.maskedinput-1.1.4.pack.js" language="javascript" type="text/javascript"></script>
<form>
	<table align="center" border="0">
		<tr>
			<th colspan="2">Buscar dados</th>
		</tr>
		<tr>
			<td align="right">CEP:</td>
			<td><input name="cep" id="cep" maxlength="8" size="11"><samp id="loading" style="display:none;">Carregando...</samp></td>
		</tr>
		<tr>
			<td>Logradouro:</td>
			<td><input name="endereco" id="endereco" size="35"></td>
		</tr>
		<tr>
			<td>Número:</td>
			<td><input name="numero" id="numero" size="8"></td>
		</tr>
		<tr>
			<td>Complemento:</td>
			<td><input name="complemento" id="complemento" size="11"></td>
		</tr>
		<tr>
			<td>Bairro:</td>
			<td><input name="bairro" id="bairro" size="11"></td>
		</tr>
		<tr>
			<td>Cidade:</td>
			<td><input name="cidade" id="cidade" size="11"></td>
		</tr>
		<tr>
			<td>Estado:</td>
			<td>
				<select name="uf" id="uf">
					<option value="">Selecione um Estado</option>
					<option value="AC">Acre</option>
					<option value="AL">Alagoas</option>
					<option value="AP">Amapá</option>
					<option value="AM">Amazonas</option>
					<option value="BA">Bahia</option>
					<option value="CE">Ceará</option>
					<option value="DF">Distrito Federal</option>
					<option value="ES">Espírito Santo</option>
					<option value="GO">Goiás</option>
					<option value="MA">Maranhão</option>
					<option value="MT">Mato Grosso</option>
					<option value="MS">Mato Grosso do Sul</option>
					<option value="MG">Minas Gerais</option>
					<option value="PA">Pará</option>
					<option value="PB">Paraíba</option>
					<option value="PR">Paraná</option>
					<option value="PE">Pernambuco</option>
					<option value="PI">Piauí</option>
					<option value="RJ">Rio de Janeiro</option>
					<option value="RN">Rio Grande do Norte</option>
					<option value="RS">Rio Grande do Sul</option>
					<option value="RO">Rondônia</option>
					<option value="RR">Roraima</option>
					<option value="SC">Santa Catarina</option>
					<option value="SP">São Paulo</option>
					<option value="SE">Sergipe</option>
					<option value="TO">Tocantins</option>
				</select>
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript" language="JavaScript">
	$(document).ready(function(){
		$('#cep').mask('99999999');
		$('#cep').focus();
		$('#cep').blur(function(){
			setTimeout(function(){
				if($('#cep').attr('value')!='' && $('#cep').attr('value')!=undefined){
					$('#loading').css({display:''});
					$.ajax({
						type: 'POST',
						dataType: 'xml',
						url: 'index.php',
						data: 'cep='+$('#cep').attr('value'),
						success:function(dataset){
							$('#loading').css({display:'none'});
							$(document).css({cursor:''});
							$(dataset).find('webservicecep').each(function(){
								switch($(this).find('resultado').text()){
									case '1':
										break;
									case '-1':
										alert('CEP não encontrado');
										break;
									case '-2':
										alert('Formato de CEP inválido');
										break;
									case '-3':
										alert('Limite de buscas de ip por minuto excedido');
										break;
									case '-4':
										alert('Ip banido. Contate o administrador');
										break;
									default:
										alert('Erro ao conectar-se tente novamente');
										break;

								}//end switch resultado
								if($(this).find('resultado').text()==1){
									$("#uf").attr({ value: $(this).find('uf').text() });
									$("#cidade").attr({ value: $(this).find('cidade').text() });
									$("#bairro").attr({ value: $(this).find('bairro').text() });
									$("#numero").attr({ value: $(this).find('numero').text() });
									$("#complemento").attr({ value: $(this).find('complemento').text() });
									$("#endereco").attr({ value: $(this).find('tipo_logradouro').text()+" "+$(this).find('logradouro').text() });
								}//end resultado true
							});//end each webservicecep
						}//end success
					});//end ajax
				}//end if value
			},1);//end setTimeout of masked
		});//end cep blur
	})//end document ready
</script>