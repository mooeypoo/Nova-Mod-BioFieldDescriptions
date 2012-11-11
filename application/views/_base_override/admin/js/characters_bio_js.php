<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $string = random_string('alnum', 8);?>

<?php
	/***************************/
	/*  BIO FORM DESCRIPTIONS  */
	/***************************/
?>
<style type="text/css">
.popover .title {
	background-color: #34363a !important;
	color: #fff !important;
	border-bottom: none !important;
	font-size: 14px;
	margin: 0px !important;
	padding: 5px !important;
}
.popover .content {
	background: #34363a !important;
	font-size: 11px;
	margin: 0px !important;
	padding: 5px !important;
}
</style>
<link rel="stylesheet" href="<?php echo base_url().MODFOLDER;?>/assets/js/css/bootstrap.css" />
<?php
	/*********************************/
	/**  END BIO FORM DESCRIPTIONS  **/
	/*********************************/
?>


<script type="text/javascript">
	function jq(myid) { 
		return myid.replace(/(:|\.)/g,'\\$1');
	}
	
	$(document).ready(function(){
	
		/*****************************/
		/**  BIO FORM DESCRIPTIONS  **/
		/*****************************/
		$('[rel=popover]').popover({
			animate: false,
			offset: 5,
			placement: 'right'
		});
		/*********************************/
		/**  END BIO FORM DESCRIPTIONS  **/
		/*********************************/
	
	
	
		$('#tabs').tabs();
		
		$('.subtabs').tabs();
		
		$('table.zebra tbody > tr:nth-child(odd)').addClass('alt');
		
		$('#list-grid').sortable({
			forcePlaceholderSize: true,
			placeholder: 'ui-state-highlight'
		});
		$('#list-grid').disableSelection();
		
		$('.add').click(function(){
			var image = $(this).parent().parent().children().eq(0).html();
			
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('ajax/save_character_image') .'/'. $id .'/'. $string;?>",
				data: { image: image },
				success: function(data){
					var content = '<li id="img_' + jq(image) +'"><a href="#" class="image upload-close" remove="' + jq(image) + '">x</a>' + data + '</li>';
					$(content).hide().appendTo('#list-grid').fadeIn();
				}
			});
			
			return false;
		});
		
		$(document).on('click', '#update', function(){
			var list = $('#list-grid').sortable('serialize');
			
			$.ajax({
				beforeSend: function(){
					$('#loading_upload_update').show();
				},
				type: "POST",
				url: "<?php echo site_url('ajax/save_character_images') .'/'. $id .'/'. $string;?>",
				data: list,
				complete: function(){
					$('#loading_upload_update').hide();
				}
			});
			
			return false;
		});
		
		$(document).on('click', '.upload-close', function(){
			var image = $(this).attr('remove');
			var index = $(this).parent().index();
			
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('ajax/del_character_image') .'/'. $id .'/'. $string;?>",
				data: { image: image },
				success: function(){
					$('#list-grid').children().eq(index).fadeOut('slow', function(){
						$(this).remove();
					});
				}
			});
			
			return false;
		});
		
		$('#position1').change(function(){
			var id = $('#position1 option:selected').val();
			
			$.ajax({
				beforeSend: function(){
					$('#loading_pos1').removeClass('hidden');
				},
				type: "POST",
				url: "<?php echo site_url('ajax/info_show_position_desc');?>",
				data: { position: id },
				success: function(data){
					$('#position1_desc').html('');
					$('#position1_desc').append(data);
				},
				complete: function(){
					$('#loading_pos1').addClass('hidden');
				}
			});
			
			return false;
		});
		
		$('#position2').change(function(){
			var id = $('#position2 option:selected').val();
			
			$.ajax({
				beforeSend: function(){
					$('#loading_pos2').removeClass('hidden');
				},
				type: "POST",
				url: "<?php echo site_url('ajax/info_show_position_desc');?>",
				data: { position: id },
				success: function(data){
					$('#position2_desc').html('');
					$('#position2_desc').append(data);
				},
				complete: function(){
					$('#loading_pos2').addClass('hidden');
				}
			});
			
			return false;
		});
		
		$('#rank').change(function(){
			var id = $('#rank option:selected').val();
			var send = {
				rank: id,
				location: '<?php echo $rankloc;?>'
			};
			
			$.ajax({
				beforeSend: function(){
					$('#loading_rank').show();
				},
				type: "POST",
				url: "<?php echo site_url('ajax/info_show_rank_img');?>",
				data: send,
				success: function(data){
					$('#rank_img').html('');
					$('#rank_img').append(data);
				},
				complete: function(){
					$('#loading_rank').hide();
				}
			});
			
			return false;
		});
		
		$('#char-activate').click(function(){
			var id = $(this).attr('myid');
			var location = '<?php echo site_url("ajax/character_activate");?>/' + id + '/<?php echo $string;?>';
			
			$.facebox(function(){
				$.get(location, function(data){
					$.facebox(data);
				});
			});
			
			return false;
		});
		
		$('#char-deactivate').click(function(){
			var id = $(this).attr('myid');
			var location = '<?php echo site_url("ajax/character_deactivate");?>/' + id + '/<?php echo $string;?>';
			
			$.facebox(function(){
				$.get(location, function(data){
					$.facebox(data);
				});
			});
			
			return false;
		});
		
		$('#char-npc').click(function(){
			var id = $(this).attr('myid');
			var location = '<?php echo site_url("ajax/character_npc");?>/' + id + '/<?php echo $string;?>';
			
			$.facebox(function(){
				$.get(location, function(data){
					$.facebox(data);
				});
			});
			
			return false;
		});
		
		$('#char-playingchar').click(function(){
			var id = $(this).attr('myid');
			var location = '<?php echo site_url("ajax/charcter_playing_character");?>/' + id + '/<?php echo $string;?>';
			
			$.facebox(function(){
				$.get(location, function(data){
					$.facebox(data);
				});
			});
			
			return false;
		});
		
		$('#loading').hide();
		$('#loaded').removeClass('hidden');

	

		
	});
</script>
