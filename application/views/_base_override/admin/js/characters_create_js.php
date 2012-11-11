<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
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
	$(document).ready(function(){
		$('#tabs').tabs();
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
	
		
		$('#position1').change(function(){
			var id = $('#position1 option:selected').val();
			
			$.ajax({
				beforeSend: function(){
					$('#loading_pos1').removeClass('hidden');
				},
				type: "POST",
				url: "<?php echo site_url('ajax/info_show_position_desc');?>",
				data: { position: id, 'nova_csrf_token': $('input[name=nova_csrf_token]').val() },
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
				data: { position: id, 'nova_csrf_token': $('input[name=nova_csrf_token]').val() },
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
				location: '<?php echo $rankloc;?>',
				'nova_csrf_token': $('input[name=nova_csrf_token]').val()
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
	});
</script>