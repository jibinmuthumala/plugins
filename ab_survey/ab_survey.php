<?php
/*
Plugin Name:ab_Survey
Author: jibin
Copyright: © 2021 jibin. All rights reserved.
*/

function abCreateTable()
{
  global $wpdb;

  if($wpdb->get_var("show tables like `$wpdb->prefix"."abform`") != "$wpdb->prefix"."abform")
  {
    $sql = "CREATE TABLE $wpdb->prefix"."abform  (`id` int(11) NOT NULL AUTO_INCREMENT,
    `first_name` varchar(256) NOT NULL,
    `last_name` varchar(256) NOT NULL,
    `email` varchar(256) NOT NULL,
    `Your_comments` varchar(500) NOT NULL,
    PRIMARY KEY id (id) );";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
  }
}
register_activation_hook( __FILE__, 'abCreateTable' );

function ab_survey()
{
    $content='';
    $content.='<h2> contact us</h2>';
    $content.='<form method="post" action="http://localhost/wordpress/2550-2">';
    $content.=' <br> <labelfor="your_first_name">first_name</label>';
    $content.=' <br> <input type="text" name="first_name" class="form control" placeholder="enter your fisrt name"/>'; 
    $content.=' <br> <labelfor="your_first_name">last_name</label>';
    $content.=' <br> <input type="text" name="last_name" class="form control" placeholder="enter your last name"/>';
    $content.=' <br> <label for="your_email">email</label>';
    $content.=' <br><input type="email" name="email" placeholder="enter your email"/>';
    $content.=' <br><label> comments</labels>';
    $content.=' <br><textarea name="your_comments" placeholder="enter your comments"></textarea>';
    $content.=' <br><input type="submit" name="form_submit" class="btn btn-md btn primary" value="submit"/>';
    $content.='</form>';



   return $content;
}
add_shortcode('test_survey','ab_survey');
function form_capture()
{
           if(isset($_POST['form_submit']))
           {
           global  $wpdb;
           $first_name=$_POST['first_name'];
           $last_name=$_POST['last_name'];
           $email=$_POST['email'];
           $your_comments=$_POST['your_comments'];

           $data=array( 
            'first_name'=>$first_name,'last_name'=>$last_name,'email'=>$email,'your_comments'=>$your_comment);

           $table_name=$wpdb->prefix.'abform';

          $wpdb->query("insert into $table_name(first_name,last_name,email,your_comments)
                                values('$first_name','$last_name','$email','$your_comments')");

       }
}
add_action('wp_head','form_capture');

function ab_admin_menu()
{
  add_menu_page('forms','form items','manage_options','ab_admin_menu','ab_admin_menu_main','dashicons-cart',6);
  add_submenu_page('ab_admin_menu','abform','Archive',
  'manage_options','ab_admin_menu_sub_archive','ab_admin_menu_sub_archive');
}
add_action('admin_menu','ab_admin_menu');
function ab_admin_menu_main()
{
  include(plugin_dir_path( __FILE__ ).'form.php');
}
function abform_footer_action_javascript() { ?>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <script type="text/javascript" >
  include_once("form.php");
  $(document).ready(function(){ 
    var show_btn=$('.show-modal');
    var show_btn=$('.show-modal');
    //$("#testmodal").modal('show');
    
      show_btn.click(function(){
        $("#testmodal").modal('show');
    })
  });
  
  $(function() {
          $('#element').on('click', function( e ) {
              Custombox.open({
                  target: '#testmodal',
                  effect: 'fadein'
              });
              e.preventDefault();
          });
      });
 

  </script>
  <script type="text/javascript" >
  ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
  
  var data = {  
        type : "post",
        'action': 'abform_update',
        'id': id
        success:function(id)
        {  $("#testmodal").modal('show');}
    
        };
        
        
  </script>
  <?php
  }  
  add_action( 'wp_footer', 'abform_footer_action_javascript' );
  add_action("wp_ajax_abform_update" , "abform_update");
  add_action("wp_ajax_nopriv_abform_update" , "abform_update");
  
  function abform_update()
  {
    global $wpdb;
    $id = $_POST['id'];
    $query = "SELECT * FROM ".$wpdb->prefix."abform"." WHERE `id` = $id";
    $fieldDetails = $wpdb->get_row( $query );
    echo json_encode($fieldDetails);
    wp_die();
  }

?>

