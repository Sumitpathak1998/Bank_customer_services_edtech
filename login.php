<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous" ></script>
</head>
<body>
<section class="d-flex justify-content-center align-items-center" style="height:600px;padding-left: 6%; padding-right: 6%;">
    <div class="box border col-sm-4" id = "login_box" style="background-color: #cfcfe540; color: #615b5b;">
        <h2 class = "mt-3" id = "heading" style = "text-align: center;font-weight:600;">Login as Customer</h2>
        <p class="mt-1" style="text-align: center;font-size: small;">Please enter username and passowrd</p>
        <form id="login_form" class="form-group">
            <div class = "col-sm-12 mt-3">
                <label style = "font-weight:600;">Username</label>
                <input type="text" id = "user_name" class = "form-control" name ="user_name">
            </div>
            <div class = "col-sm-12 mt-3">
                <label style = "font-weight:600;">Password</label>
                <input type="text" id = "password" class = "form-control" placeholder="*********" name = "password" >
                <span style="color: red;font-size: small;" id="empty_password"></span>
            </div>
            <div class = "col-sm-4 mt-3">
                <button type = "button" class = "btn btn-success" id = "submit_button">Log In</button>
            </div>
        </form>
        <div class="row">
            <input type="button" value="Admin Login" class="col-sm-5 mt-2 ml-4 mr-2 mb-2 btn btn-danger" id = "admin_button">
            <input type="button" value="Customer Login" class="col-sm-5 mt-2 ml-2 mr-2 mb-2 btn btn-primary" id = "customer_button">
        </div>    
    </div>
</section>
    
<script>
$("#admin_button").bind('click',function(){
    $("#heading").html("Login as Admin");
})
$("#customer_button").bind('click',function(){
    $("#heading").html("Login as Customer");
})

$("#submit_button").bind("click",function(){
    let data = $("#login_form").serialize();
    if (data.indexOf('=&') != (-1) || data.substr(data.length - 1) == '=') {
        $("#empty_password").html("enter both username and password");
        return ($("#user_name").val() == '') ? $("#user_name").focus() : $("#password").focus(); 
    }
    $("#empty_password").html("");
    let flag = ($("#heading").text().includes("Admin")) ? "1" : "2"; 
    $.ajax({
        method : "post" ,
        url : "BankOpreation.php" ,
        data : data+"&flag="+flag+"&method=checkLogin",  
        success :function(data) {
            if(data === "login success") {
                if (flag == 1) {
                    window.location.replace("adminpage.php");    
                } else {
                    window.location.replace("customerpage.php?user="+$("#user_name").val()); 
                }
            } else {
                alert(data);
            }
        }
    });
})

</script>
</body>
</html>