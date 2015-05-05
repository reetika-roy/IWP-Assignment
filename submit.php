<?php
include_once("lib/connection.php"); 
if(!empty($_REQUEST['mode']))
{
  $first_name = cleanData($_POST['firstName']);
  $last_name = cleanData($_POST['lastName']);
  $email = cleanData($_POST['email']);

  $sql_check = $mysqli->query("SELECT id FROM users WHERE email='$email'");
  $finalcount= $sql_check->num_rows;
  if($finalcount == '0')
  {
     $query = "INSERT INTO users SET 
        `first_name`='$first_name',
        `last_name`='$last_name',
        `email`='$email';"; 
        $res= $mysqli->query($query);
        if(!$res)
         echo $mysqli->error;
       else
         {
            header("Location: index.php?msg=User added");
        }
  }
  else
    header("Location: index.php?msg=User exists");
}

 function cleanData($data)
{
  $data=trim($data);
  $data=stripcslashes($data);
  $data=htmlspecialchars($data);
  $data=strip_tags($data);
  return $data;
}
?>