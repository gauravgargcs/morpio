<?php $email = get_lising_connected_email();?>
<?php if($email){ ?>
<iframe src="<?=DEAL_ROOM_URL;?>dashboard/?display=embbed&token=<?=base64_encode($email);?>;" height="2000px" width="100%"></iframe>
<script type="text/javascript">
	$(document).ready(function () {
		$('#vertical-menu-btn').trigger('click');
	})
</script>
 <?php }else{
 	?> 
 	<p class="text-danger text-center">No owner email account found.</p>

 <?php } ?> 