<?php if ( !is_user_logged_in() ) {
  wp_login_form();
}
else { ?>
<a href="<?php echo get_permalink(get_the_ID());?>" class="btn btn-warning btn-sm">< Back</a>
<!-- <h2> Tickets Detail</h2> -->
<?php if(count($result)>0){
?>
<p><b>Name</b>:<?=$result[0]->name?></p>
<p><b>phone</b>:<?=$result[0]->phone?></p>
<p><b>Issue</b>:<?=$result[0]->issue?></p>
<p><b>description</b>:<?=$result[0]->description?></p>
<p><b>Status</b>:
<span class="badge <?=$result[0]->status=='pending'?' badge-warning':'badge-success'?>"><?=$result[0]->status?></span></p>
<div class="action-btn">
<?php if($result[0]->status=='pending'){?>  
<button id="complete_ticket" data-id="<?=$value->ticket_id;?>" data-url="<?php echo admin_url( 'admin-ajax.php' ); ?>" class="btn btn-success btn-sm">complete</button>
<?php } ?>
</div>

<h2>Revert </h2>
<?php if(count($resultrevert)>0){
    foreach($resultrevert as $revert){
        ?>
        <div class="mesage">
            <p><b>User</b>:<?=$current_user_id==$revert->user_id?"You":(current_user_can('administrator')?'User':"Admin")?><br/>
        <b>Message</b>:<?=$revert->revert?><br/> <small><?=date('d-m-Y h:i A',$revert->revert_date)?></small></p>
    </div>
        <?php
    }
}?>
<form id="ticket_form_revert" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="post">
    <input type="hidden" name="action" value="save_ticket_revert" required>
    <input type="hidden" name="ticket_id" value="<?=$result[0]->ticket_id?>" required>
  <div class="form-group">
  <label for="description">message:</label>
  <textarea  id="revert" name="revert" class="form-control"  Placeholder="message Here"></textarea>
  </div>
  <button type="submit" class="btn btn-warning"> Message</button>
 <div class="response"></div>
</form>
<?php }else{ ?>


<?php }?>
<?php } ?>