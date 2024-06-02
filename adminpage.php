<?php

spl_autoload_register(function($class_name) {
    include str_replace('\\', '/', $class_name) . '.php';
});

$conn = new Connection();
$pdo = $conn->getConnection();

$stmt = $pdo->prepare('SELECT * FROM `transaction`');
$stmt->execute();
$record = [];
if ($stmt->rowCount() > 0) {
    $record = $stmt->fetchAll(PDo::FETCH_ASSOC);
}

$conn->disconnected();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous" ></script>
    <title>Admin Page</title>
</head>
<body>
<nav class="navbar navbar-light ml-2 mr-2 mt-3" style="background-color: #f3f6f8;border-radius: 20px;">
    <div class = "d-flex justify-content-between col-sm-12">
        <div class="mt-3 ml-5" style="font-size:200%; font-weight: 600;font-family: sans-serif;">Welcome Admin Page</div>
        <div class="mt-3 mr-5">
            <button type="submit" class = "btn btn-primary" id = "log_out">Log out</button>
        </div>
    </div>
</nav>
<div class="col-sm-12 d-flex justify-content-end mr-3 mb-2 mt-3">
    <button type="submit" class="col-sm-1 btn btn-success" id = "add_customer" data-toggle = "modal" data-target = "#mymodal" >+Add</button>
</div>
<div class="col-sm-12 mt-4 px-5" style="overflow: scroll;">
    <table class = "table table-striped">
        <thead>
            <tr>
                <th>Transaction id</th>
                <th>Name</th>
                <th>Date</th>
                <th>Dabit</th>
                <th>Credit</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody id="tbl_body">
            <?php foreach($record as $value) {?>
            <tr>
                <td><?=$value['tran_id']?></td>
                <td><?=$value['name']?></td>
                <td><?=$value['date']?></td>
                <td><?=$value['debit']?></td>
                <td><?=$value['credit']?></td>
                <td><?=$value['amount']?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div id = "mymodal" class="modal fade" role="dialog">
    <div class="modal-dialog" >
        <div class = "modal-content" style="background-color: #f3f6f8; color:#575f7f">
            <div class="modal-header" >
                <h3 class = "modal-title" style="font-weight: 600;text-align: center;">Add Customer</h3>
                <button type="button" class = "close btn" id = "close_addcustomer" data-dismiss="modal">X</button>
            </div>
            <div class="modal-body">
                <form id = "customerForm" class = "form-group">
                    <div class="col-sm-12 mt-2">
                        <label style = "font-weight:600;">Customer Name</label>
                        <input type="text" id = "customer_name" class = "form-control" name = "customer_name">
                    </div>
                    <div class="col-sm-12 mt-2">
                        <label style = "font-weight:600;">Password</label>
                        <input type="text" id = "pwd" class = "form-control" placeholder="*********" name = "pwd">
                        <span style="color: red;font-size: smaller;" id = "empty_customer"></span>
                    </div>
                    <div class = "col-sm-4 mt-3">
                        <button type = "button" class = "btn btn-success" id = "submit_customer">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script> 

$("#submit_customer").bind('click',function() {
    let data = $("#customerForm").serialize();
    if (data.indexOf('=&') != (-1) || data.substring(data.length-1) == '=' ) {
        $("#empty_customer").html("Please fill both the filds");
        return ($("#customer_name").val() == '') ? $("#customer_name").focus() : $("#pwd").focus();
    }
    $("#empty_customer").html("");
    $("#close_addcustomer").trigger('click');
    $.ajax({
        method :'post' ,
        url : 'BankOpreation.php',
        data : data+"&method=insertCustomer",
        success : function (data) {
            $("#customerForm")[0].reset();
            alert(data);
            
        }
    })
}); 

$("#log_out").bind('click',function(){
    window.location.replace("login.php");
});

</script>
</body>
</html>