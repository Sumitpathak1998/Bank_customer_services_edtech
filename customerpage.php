<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous" ></script>
</head>
<body>
<nav class="navbar navbar-light ml-2 mr-2 mt-3" style="background-color: #f3f6f8;border-radius: 20px;">
    <div class = "d-flex justify-content-between col-sm-12">
        <div class="mt-3 ml-5" style="font-size:200%; font-weight: 600;font-family: sans-serif;">Welcome <?=$_REQUEST['user']?></div>
        <div class="mt-3 mr-5">
            <button type="submit" class = "btn btn-danger" id = "log_out">Log out</button>
        </div>
    </div>
</nav>
<section class="ml-5 mt-4 mr-5 px-4 pb-4" style="background-color: #f3f6f8;border-radius: 20px;">
    <div class="mt-3 ml-5 pt-4" style="font-size:150%; font-weight: 550;font-family: sans-serif;">What action you want to perform</div>
    <div class="mt-3 ml-5">
        <button class="btn btn-info col-sm-2" id ="dabit" data-toggle = "modal" data-target = "#debitcredit_modal">Debit Amount</button>
    </div>
    <div class="mt-3 ml-5">
        <button class="btn btn-info col-sm-2" id ="credit" data-toggle = "modal" data-target = "#debitcredit_modal">Credit Amount</button>
    </div>
    <div class="mt-3 ml-5">
        <button class="btn btn-info col-sm-2" id="money_transfer" data-toggle = "modal" data-target = "#moneytransfer_modal">Money Transfer</button>
    </div>
    <div class="mt-3 ml-5">
        <button class="btn btn-info col-sm-2" id="alltransaction" data-toggle = "modal" data-target = "#alltransaction_modal">All Transaction</button>
    </div>
</section>

<!-- Debit and credit amount modal -->
<div id = "debitcredit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class = "modal-content" style="background-color: #f3f6f8; color:#575f7f">
            <div class="modal-header" >
                <h3 class = "modal-title" style="font-weight: 600;text-align: center;" id = "heading"></h3>
                <button type="button" class = "close btn" id="close_modal" data-dismiss="modal">X</button>
            </div>
            <div class="modal-body">
                <form id = "debitcredit_form" class = "form-group">
                    <div class="col-sm-12 mt-2">
                        <label style = "font-weight:600;" id = "action"></label>
                        <input type="text" id = "amount" class = "form-control" name = "amount" onkeypress="return /[0-9,\.]/i.test(event.key)">
                        <span style="color: red;font-size: smaller;" id = "empty_amount"></span>
                    </div>
                    <div class = "col-sm-4 mt-3">
                        <button type = "button" class = "btn btn-success" id = "submit_action">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Money transfer modal -->
<div id = "moneytransfer_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class = "modal-content" style="background-color: #f3f6f8; color:#575f7f">
            <div class="modal-header" >
                <h3 class = "modal-title" style="font-weight: 600;text-align: center;" id = "heading">Transfer Money</h3>
                <button type="button" class = "close btn" id="moneytranfer_close_modal" data-dismiss="modal">X</button>
            </div>
            <div class="modal-body">
                <form id = "moneytransfer_form" class = "form-group">
                    <div class="col-sm-12 mt-2">
                        <label style = "font-weight:600;" >Enter Amount</label>
                        <input type="text" id = "amount" class = "form-control" name = "amount" onkeypress="return /[0-9,\.]/i.test(event.key)">
                    </div>
                    <div class="col-sm-12 mt-2">
                        <label style = "font-weight:600;" >Receiver Name</label>
                        <input type="text" id = "receiver_name" class = "form-control" name = "receiver_name" >
                    </div>
                    <div class="col-sm-12 mt-2">
                        <label style = "font-weight:600;" >Receiver Unique Id</label>
                        <input type="text" id = "unique_id" class = "form-control" name = "unique_id">
                        <span style="color: red;font-size: smaller;" id="empty_fields"></span>
                    </div>
                    <div class = "col-sm-4 mt-3">
                        <button type = "button" class = "btn btn-success" id = "submit_details">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id = "alltransaction_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class = "modal-content" style="background-color: #f3f6f8; color:#575f7f">
            <div class="modal-header" >
                <h3 class = "modal-title" style="font-weight: 600;text-align: center;" id = "heading">Transaction Details</h3>
                <button type="button" class = "close btn" id="alltransaction_close_modal" data-dismiss="modal">X</button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12 mt-4">
                    <table class = "table table-striped">
                        <thead>
                            <tr>
                                <th>Transaction id</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="tbl_body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$("#dabit").bind('click',function(){
    $("#heading").html("Debit Amount");
    $("#action").html("action").html("Enter the amount you want to debit");
}); 

$("#credit").bind('click',function(){
    $("#heading").html("Credit Amount");
    $("#action").html("action").html("Enter the amount you want to credit");
}); 

$("#submit_action").bind('click', function(){
    let amount = $("#amount").val();
    if (amount == '') {
        $("#empty_amount").html("Please enter the amount");
        return $("#amount").focus();
    }
    $("#empty_amount").html("");
    let method = ($("#heading").text().includes("Credit")) ? "creditAmount" : "debitAmount";
    $("#close_modal").trigger("click");
    $.ajax({
        method : 'post',
        url : 'BankOpreation.php',
        data : {"amount": amount , "user" : "<?=$_REQUEST['user']?>" , "method" : method},
        success : function(data){
            $("#debitcredit_form")[0].reset();
            alert(data);
        }
    })
});

$("#submit_details").bind('click', function() {
    let data = $("#moneytransfer_form").serialize();
    if (data.indexOf('=&') != (-1) || data.substr(data.length-1) == '=') {
        $("#empty_fields").html("please fill all the input fields");
        if ($("#amount").val() == '') return $("#amount").focus();
        return ($("#receiver_name").val() == '') ? $("#receiver_name").focus() : $("#unique_id").focus();   
    }
    $("#empty_fields").html("");
    $("#moneytranfer_close_modal").trigger("click");
    $.ajax({
        method : 'post' ,
        url : 'BankOpreation.php',
        data :data+"&method=moneyTransfer&user=<?=$_REQUEST['user']?>",
        success :function(data) {
            $("#moneytransfer_form")[0].reset();
            alert(data);
        }
    })
});

$("#alltransaction").bind('click',function(){
    $.ajax({
        method : 'post', 
        url : 'BankOpreation.php',
        data : {"method" : "seeAlltransactionByCustomer" , "user" : "<?=$_REQUEST['user']?>"} ,
        success : function(data) {
            $("#tbl_body").html(data);
        }
    })
});

$("#log_out").bind('click',function(){
    window.location.replace("login.php");
});

</script>
</body>
</html>