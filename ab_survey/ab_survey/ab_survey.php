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
    `comments` varchar(500) NOT NULL,
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
    $content.=' <br><textarea name="comments" placeholder="enter your comments"></textarea>';
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
           $comments=$_POST['comments'];
           $listID = "3a0dcb5191";
           $apiKey = "478b51d4ced6862be529ea5d9ca6f754-us6";

           $data=array( 
           'first_name'=>$first_name,'last_name'=>$last_name,'email'=>$email,'comments'=>$comments);
 
           $table_name=$wpdb->prefix.'abform';

          $wpdb->query("insert into $table_name(first_name,last_name,email,comments)
                                values('$first_name','$last_name','$email','$comments')");
                                

$memberID = md5(strtolower($email));
$dataCenter = substr($apiKey,strpos($apiKey,"-")+1);
$url = "https://" . $dataCenter . ".api.mailchimp.com/3.0/lists/" . $listID . "/members/" . $memberID;

// member information
$json = json_encode([
    "email_address" => $email,
    "status" => "subscribed",
    "merge_fields" => [
    "FIRST_NAME" => $first_name,
    "LAST_NAME" => $last_name,
    "COMMENTS" => $comments,
  ]
]);

// send a HTTP POST request with curl

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERPWD, "user:" . $apiKey);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//echo "<Pre>"; print_r(json_decode($result,true)); print_r($httpCode);
curl_close($ch);
if ($httpCode == 200) {
  //your success code
  $msg = "OK";
} 
else {
  switch ($httpCode) {
    case 214:
      $msg = "You are already subscribed.";
    break;
    
    default:
      $msg = "Some problem occurred, please try again.";
    break;
  }
}
echo $msg;
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
  add_action( 'admin_footer', 'abform_footer_action_javascript' );
}
function abform_footer_action_javascript() { ?>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <script type="text/javascript" >
 function edit_contact(id){   
  ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
  
  var data = {  
        type : "post",
        'action': 'abform_update',
        'id': id
  };
        jQuery.post(ajaxurl, data, function(response) {
        responseData = jQuery.parseJSON( response );
        if(responseData)
        {
          jQuery('#id').val(responseData.id);
          jQuery('#first_name').val(responseData.first_name);
          jQuery('#last_name').val(responseData.last_name);
          jQuery('#email').val(responseData.email);
          jQuery('#comments').val(responseData.comments);
          
          $("#testmodal").modal('show');
        }
      });
        
     } 
        
        
  </script>
  <?php
  }
   
   
  
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
