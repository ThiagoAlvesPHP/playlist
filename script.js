$(function(){

	$(document).on('click', '.music', function(e){
		e.preventDefault();

		let music = $(this).val();

		$.ajax({
			url:'ajax.php',
			type:'post',
			dataType:'json',
			data:{music:music},
			success:function(json){
				let src = 'arquivos/'+json['musica'];

				//$('#music').removeAttr('src');
				$('#music').attr('src', src);
				$('#descricao').html(json['descricao'].substring(0, json['descricao'].length - 4));
			}
		});
	});

});