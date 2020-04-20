<?php if ( !is_user_logged_in() ) {
  wp_login_form();
}
else { ?>

<!-- <h2>Tickets</h2> -->

<table>
  <tr>
    <th>#</th>
    <th>name</th>
    <th>Phone</th>
    <th>Issue</th>
    <th>status</th>
    <th>Action</th>
  </tr>
  <?php if(count( $results)>0){
   foreach($results as $key=>$value){
   ?>

  <tr>
    <td><?=$key+1?></td>
    <td><?=$value->name?></td>
    <td><?=$value->phone?></td>
    <td><?=$value->issue?></td>
    <td><span class="badge <?=$value->status=='pending'?' badge-warning':'badge-success'?>"><?=$value->status?></span></td>
    <td style="text-align:right">
    <?php if($value->status=='pending'){?>  
    <button id="complete_ticket" data-id="<?=$value->ticket_id;?>" data-url="<?php echo admin_url( 'admin-ajax.php' ); ?>" class="btn btn-success btn-sm">complete</button>
    <?php } ?>
    <a class="btn btn-info btn-sm" href="?ticket_id=<?=$value->ticket_id;?>">view</a></td>
  </tr>
   <?php }?>
  
   <?php }else{?>
   <tr>
    <th colspan="6" style="text-align:center">No Ticket created</th>
    </tr>
   <?php }?>
</table>

<?php } ?>