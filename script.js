$(function(){

	const som = document.getElementById("music");

	som.onloadeddata = function() {
	   	let duracao = parseInt(som.duration)+'000';

	   	let musica = function(){
	   		$.ajax({
                url:'ajax.php',
                type:'POST',
                dataType:'json',
                data:{duracao:duracao},
                success:function(json){
                    let src = 'arquivos/'+json['musica'];
				
					$('#music').attr('src', src);
					$('#descricao').html(json['descricao'].substring(0, json['descricao'].length - 4));
                }
            });
	   	}
	   	setInterval(musica, duracao);
	};

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
				
				$('#music').attr('src', src);
				$('#descricao').html(json['descricao'].substring(0, json['descricao'].length - 4));
			}
		});
	});

});