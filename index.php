<?php
include_once("lib/connection.php"); 
$msg="";
if(isset($_REQUEST['msg']))
{
  $msg= $_REQUEST['msg'];
}
$q = $mysqli->query("SELECT * FROM users ORDER BY id");
?>

<!DOCTYPE html>
<html>

<head>

  <meta charset="UTF-8">

  <title>Mailing List</title>

  <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>

  <link rel="stylesheet" href="css/normalize.css">

    <link rel="stylesheet" href="css/style.css">

    <script type="text/javascript">
      function validate()  
            {  
            var firstName= document.mailList.firstName.value;
            var lastName= document.mailList.lastName.value;
            var email= document.mailList.email;
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
              if(firstName==''||firstName.match("First Name"))
                    {  
                            alert("You have not entered your first name!");
                            document.mailList.firstName.focus(); 
                            return false;
                    }
                if(lastName==''||firstName.match("Last Name"))
                    {  
                            alert("You have not entered your last name!");
                            document.mailList.lastName.focus(); 
                            return false;
                    }  
                if(email.value.match(mailformat))  
                    {  
                        document.mailList.submit();
                        return true;
                    }  
                else  
                    {  
                            alert("You have entered an invalid email address!");
                            document.mailList.email.focus();
                            return false;
                    }  

            }
    </script>
    <script type="text/javascript">
      function submit()  
            {  
                        document.mail.submit();

            }
    </script>

</head>

<body>

  <div class="form">
      <p style="color:white; text-align:center; font-weight:500; font-size:24px;"><?php echo $msg;?></p>
      <ul class="tab-group">
        <li class="tab active"><a href="#signup">Add Users</a></li>
        <li class="tab"><a href="#login">Send Mails</a></li>
      </ul>

      <div class="tab-content">
        <div id="signup">   
          <h1>Add New Users</h1>

          <form name="mailList" id="mailList" action="submit.php" method="POST">
                  <input type="hidden" name="mode" value="1" /> 

          <div class="top-row">
            <div class="field-wrap">
              <label>
                First Name<span class="req">*</span>
              </label>
              <input type="text" name="firstName" id="firstName" required autocomplete="off" />
            </div>

            <div class="field-wrap">
              <label>
                Last Name<span class="req">*</span>
              </label>
              <input type="text" name="lastName" id="lastName" required autocomplete="off"/>
            </div>
          </div>

          <div class="field-wrap">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="email" name="email" id="email" required autocomplete="off"/>
          </div>

          <button type="submit" class="button button-block" onClick="return validate()"/>Add</button>

          </form>

        </div>

        <div id="login">   
          <h1>Mailing List</h1>

            <form name="mail" id="mail" action="mail.php" method="POST">
                  <input type="hidden" name="mode1" value="1" />
                  <?php while($data= $q->fetch_array()) { ?>
            <div class="field-wrap">
              <input type="checkbox" value="<?php echo $data['id']?>" id="squaredFour" name="users[]" style="margin-left:-120px;"/>
              <label for="squaredFour" style="margin-left: 150px;top: -25px;"><?php echo $data['first_name']." ".$data['last_name'];?></label>
          </div>
          <?php } ?>

          <button class="button button-block" onClick="submit()"/>Send</button>

          </form>

        </div>

      </div><!-- tab-content -->

</div> <!-- /form -->

  <script src='js/jquery.js'></script>

  <script src="js/index.js"></script>

</body>

</html>