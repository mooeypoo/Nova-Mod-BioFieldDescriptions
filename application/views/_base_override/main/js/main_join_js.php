<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $string = random_string('alnum', 8);?>

<?php
	/***************************/
	/*  BIO FORM DESCRIPTIONS  */
	/***************************/
?>
		<script type="text/javascript" src="<?php echo base_url().MODFOLDER.'/assets/js/jquery.lazy.js';?>"></script>

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

		var $tabs = $('#tabs').tabs();
		/*****************************/
		/**  BIO FORM DESCRIPTIONS  **/
		/*****************************/	  
				$.lazy({
					src: '<?php echo base_url() . MODFOLDER;?>/assets/js/bootstrap-twipsy.js',
					name: 'twipsy',
					dependencies: {
						css: ['<?php echo base_url() . MODFOLDER;?>/assets/js/css/bootstrap.css']
					},
					cache: true
				});
				
				$.lazy({
					src: '<?php echo base_url() . MODFOLDER;?>/assets/js/bootstrap-popover.js',
					name: 'popover',
					dependencies: {
						js: ['<?php echo base_url() . MODFOLDER;?>/assets/js/bootstrap-twipsy.js'],
						css: ['<?php echo base_url() . MODFOLDER;?>/assets/js/css/bootstrap.css']
					},
					cache: true
				});

		$('[rel=popover]').popover({
			animate: false,
			offset: 5,
			placement: 'right'
		});
		/*********************************/
		/**  END BIO FORM DESCRIPTIONS  **/
		/*********************************/
	
		
		$('#nextTab').click(function(){
			var value = parseInt($tabs.tabs('option', 'selected'));
			var length = $tabs.tabs('length');
			
			value = value + 1;
			length = length - 1;
			
			if (value <= length)
				$tabs.tabs('select', value);
			
			return false;
		});
		
		$('#submitJoin').click(function(){
			return confirm('<?php echo lang('confirm_join');?>');
		});
		
		$('#position').change(function(){
			var id = $('#position option:selected').val();
			
			$('#loading_update').ajaxStart(function(){
				$(this).show();
			});
			
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('ajax/info_show_position_desc');?>",
				data: { position: id, 'nova_csrf_token': $('input[name=nova_csrf_token]').val() },
				success: function(data){
					$('#position_desc').html('');
					$('#position_desc').append(data);
				}
			});
			
			$('#loading_update').ajaxStop(function(){
				$(this).hide();
			});
			
			return false;
		});
	});
</script>