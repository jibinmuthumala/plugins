<?php
    
    global $wpdb;
    $table_name=$wpdb->prefix.'abform';
    $result=$wpdb->get_results("select * from $table_name");

?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
  <br>
  <br>
  <br>
  <h1>DATA FROM DATABASE</h1>
<table border="1" >
  <tr>
    <th>ID</th>
    <th>first name</th>
    <th>lastname</th>
    <th>email</th>
    <th>Comments</th>
  </tr>
  <?php
  
    foreach($result as $data)
  {
    ?>
  <tr>
    <td><?php echo $data->id;?></td>
    <td><?php echo $data->first_name;?></td>
    <td><?php echo $data->last_name;?></td>
    <td><?php echo $data->email;?></td>
    <td><?php echo $data->comments;?></td>
    <td><button id="element" class="show-modal" 
             onClick="edit_contact('<?php echo $data->id;?>');" >edit</button> 
      
  </tr>
  <?php
}
?>
</table>


<div id="testmodal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Update Your details</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="" >
            <table class="table table-bordered" id="testmodal">
              <tr>
                <th>first Name  </th>
                <td><input type="text" name="first_name" id="first_name" value="<?php echo $data->first_name;?>"></td>
              </tr>
              <tr>
                <th> lastName  </th>
                <td><input type="text" name="last_name" id="last_name" value="<?php echo $data->last_name;?>"></td>
              </tr>
              <br>
              <tr>
                <th>Email id </th>
                <td><input type="text" name="email" id="email" value="<?php echo $data->email;?>">
                  <input type="hidden" name="id" id="id"></td>
              </tr>
              <br>
              <tr>
                <th>Comments  </th>
                <td><input type="text" name="comments" id="comments" value="<?php echo $data->comments;?>"></td>
              </tr>
              <br>
              <div class="modal-footer">
               <tr colspan="2">
                <td colspan="2"><center><input type="submit" name="submit"  class="btn btn-success"></center></td>
              </tr>
            </div>
            </table>
          </form>
            </div>
            
        </div>
    </div>
</div>
</body>

</html>
<?php

global $wpdb;

        $id= $data->id;
    $table_name=$wpdb->prefix.'abform';
          
            if (isset($_POST['submit'])) 
            {

             $data =array(
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email'    => $_POST['email'],
                'comments'=>$_POST['comments']
                    );
            $wherecondition=array( 'id'=>$_POST['id'] );

            $wpdb->update($table_name, $data, $wherecondition);
                
            } 

       ?>