<?php if ( !is_user_logged_in() ) {
  wp_login_form();
}
else { ?>
<form id="ticket_form" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="post">
    <input type="hidden" name="action" value="save_ticket" required>
    
<div class="form-group">
  <label for="name">Name</label><br>
  <input type="text" id="name" class="form-control" name="name" value="" placeholder="Name here">
  </div>
  <div class="form-group">
  <label for="phone">Phone:</label>
  <input type="text" id="phone" class="form-control" name="phone" value="" Placeholder="Phone Here">
  </div>
  <div class="form-group">
  <label for="issue">Issue:</label>
  <input type="text" id="issue" class="form-control" name="issue" value="" Placeholder="Issue Here">
  </div>
  <div class="form-group">
  <label for="description">Description:</label>
  <textarea  id="description" name="description" class="form-control"  Placeholder="Description Here"></textarea>
  </div>
  <button type="submit" class="btn btn-success"> Create</button>
 <div class="response"></div>
</form>
<?php } ?>