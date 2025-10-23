<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>The Rivera</title>
<meta name="robots" content="noindex" />
<link rel="shortcut icon" type="image/x-icon" href="static/images/favicon.png"/>
 

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">


<style>
body {font-family: "Red Hat Display", sans-serif;font-weight:300; font-size:15px; }
.page-content-wrapper {width:1300px; margin:0 auto;}
th {border-top: 1px solid #4d4d4d; background:#4d4d4d; color:#fff; font-weight:300; text-align:left}
h1 {color:#02254D; font-size: 25px; text-align: center;}
button.dt-butto { background:#02254D !important; font-size:13px !important; font-weight:500 !important; color:#fff !important }
.logo {  text-align:center; margin:20px auto 15px}


 
/* Login Modal */
#loginModal {
    display: none; 
    position: fixed; 
    z-index: 9999; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgba(0,0,0,0.7); 
}

#loginModalContent {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 30px;
    border: 1px solid #888;
    width: 300px;
    text-align: center;
    border-radius: 10px;
}

#loginModal input[type="text"], #loginModal input[type="password"] {
    width: 90%;
    padding: 10px;
    margin: 10px 0;
}

#loginModal button {
    padding: 10px 20px;
    background-color: #02254D;
    border: none;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
}
 




</style>

</head>

<body>
    
    
<div id="loginModal">
  <div id="loginModalContent">
    <h2>Login</h2>
    <input type="text" id="username" placeholder="Username" /><br/>
    <input type="password" id="password" placeholder="Password" /><br/>
    <button onclick="checkLogin()">Login</button>
    <p id="errorMsg" style="color:red; margin-top:10px;"></p>
  </div>
</div>
    
    

<?php
ob_start();

// Enable error reporting (for development only, remove on live)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
if($_SERVER['HTTP_HOST'] == '192.168.0.60'){
    $serverName = "localhost";
    $userName = "root";
    $passWord = "";
    $dataBase = "pixelstudio_db";    
}else{
    $servername = "localhost";
    $username = "phpmyadmin";
    $password = 'Neuberg@1243';
    $database = 'neuberg_campaigns_db';
}

$connect = mysqli_connect($serverName, $userName, $passWord, $dataBase);

// Check connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch enquiries

$query = "SELECT *, DATE_FORMAT(created,'%d-%m-%Y') AS Date FROM quickenquiry ORDER BY id DESC";
$result = mysqli_query($connect, $query);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

ob_flush();
?>

<div class="page-container">
  <div class="page-content-wrapper">
    <div class="page-content"> 
      <div class="page-head"> 
        <div class="logo">
          <img src="https://ufactor.in/the-rivera/static/images/logo.svg" border="0" />
        </div>
        <div class="page-title">
          <h1>The Rivera Enquiry</h1>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <form id="frmReguserlist" name="frmReguserlist" role="form" action="#" method="post">
              <div class="box-body">
                <div class="col-md-3"></div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="actions"></div>
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <input type="hidden" name="disptblname" id="disptblname" value="blogenquiry" />
              </div>
              <table id="tblresult" class="display" style="width:100%; border:1px solid #d5d5d5;">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Name</th> 
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Email</th>
                    <th>Interest</th>
                    <!-- <th>Action</th> -->
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($data as $value) { ?>
                  <tr id="row_<?php echo $value['id']; ?>">
    <td><?php echo $value['created']; ?></td>
    <td><?php echo $value['name']; ?></td>
    <td><?php echo $value['phone']; ?></td>
    <td><?php echo $value['email']; ?></td>
    <td><?php echo $value['interest']; ?></td>
    <td><?php echo $value['source']; ?></td>
   <!--   <td>
       <button class="delete-btn" data-id="<?php echo $value['id']; ?>">Delete</button> 
    </td>-->
</tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Scripts -->
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() { 
    $('#tblresult').DataTable({
        pageLength: 50, // Show 50 entries per page
        order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: 'Download'
            },
        ]
    });
});


$(document).ready(function() {

    // Attach click event to delete buttons
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this entry?')) {
            $.ajax({
                url: 'delete_entry.php', // Create this PHP file
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response == 'success') {
                        $('#row_' + id).remove(); // Remove row from table
                    } else {
                        alert('Failed to delete.');
                    }
                }
            });
        }
    });

});


</script>

<script>
// Hide page content until login
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.page-container').style.display = 'none';
    document.getElementById('loginModal').style.display = 'block';
});

function checkLogin() {
    var user = document.getElementById('username').value;
    var pass = document.getElementById('password').value;
    
    // Static Username and Password
    if(user === 'sales@ufactor.in' && pass === 'ufactor@321') {
        document.getElementById('loginModal').style.display = 'none';
        document.querySelector('.page-container').style.display = 'block';
    } else {
        document.getElementById('errorMsg').innerText = "Invalid username or password!";
    }
}
</script>

<script>
// Prevent Right Click
document.addEventListener('contextmenu', event => event.preventDefault());

// Prevent F12, Ctrl+Shift+I, Ctrl+U
document.onkeydown = function(e) {
    if (e.keyCode == 123) { // F12
        return false;
    }
    if (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74)) { // Ctrl+Shift+I or Ctrl+Shift+J
        return false;
    }
    if (e.ctrlKey && e.keyCode == 85) { // Ctrl+U
        return false;
    }
    if (e.ctrlKey && e.keyCode == 83) { // Ctrl+S (Save)
        return false;
    }
};

// Prevent text selection
document.addEventListener('selectstart', function(e) {
    e.preventDefault();
});

// Optional: Prevent Dragging
document.addEventListener('dragstart', function(e) {
    e.preventDefault();
});
</script>



</body>
</html>
