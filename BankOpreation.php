<?php

spl_autoload_register(function ($class_name) {
    @include  str_replace('\\', '/', $class_name) . '.php';
});

$obj = new BankOpreation();
$obj->request = $_REQUEST;
$conn = new Connection();
$obj->pdo = $conn->getConnection(); 
if (!($obj->pdo)) {
    echo "Data base connection failed to established";
    exit; 
} 
if ( array_key_exists('method',$obj->request) && !empty($obj->request['method'])) {
    switch($obj->request['method']) {
        case 'checkLogin' :
            if($obj->checkLogin()) {
                echo "login success"; 
            } else {
                echo "User not present";
            }
            break;
        case 'insertCustomer' :
            $insert_res = $obj->insertCustomer();
            echo $insert_res;
            break;
        case 'creditAmount' :
            $credit_res = $obj->creditAmount();
            echo $credit_res;
            break;
        case 'debitAmount' :
            $debit_res = $obj->debitAmount();
            echo $debit_res;
            break;
        case 'moneyTransfer':
            $moneytran_res = $obj->moneyTransfer();
            echo $moneytran_res;
            break;
        case "seeAlltransactionByCustomer":
            echo $obj->seeAlltransactionByCustomer();
            break;
    }
}

$conn->disconnected();
exit;

class BankOpreation {

    public $pdo;
    public $request;

    public function checkLogin() : bool {
        $stmt = '';
        if ($this->request['flag'] == 1) {
            $stmt = $this->pdo->prepare('SELECT * FROM `admin` WHERE `user`=? and `pass`=?');
        } else {
            $stmt = $this->pdo->prepare('SELECT * FROM `customer` where `username`=? and `password`=?');
        }
        $stmt->execute([$this->request['user_name'],$this->request['password']]);
        return ($stmt->rowCount() > 0) ? true : false;
    }

    public function insertCustomer(): string {
        try {
            $customer_uniqueId = $this->randomNumber(5);
            $stmt = $this->pdo->prepare('INSERT INTO `customer`(`id`,`username`,`password`,`amount`) VALUES (?,?,?,?)');
            $stmt->execute([$customer_uniqueId,$this->request['customer_name'],$this->request['pwd'],0]);
            return "Customer added";
        } catch (Exception $e) {
            return "Error : " .$e->getMessage() . " in line : " . $e->getLine() ; 
        }
    }

    public function creditAmount() : string {
        try{
            $status = '';
            $present_amount = $this->getAmountByUser($this->request['user']);
            $added_amount = $present_amount + $this->request['amount'];
            if($this->inserttransactionDetails($this->request['user'],$this->request['amount'],$added_amount,'credit')) {
                $status = "Transaction successful,";
            } else {
                return "Transaction not successful";
            }
            $status .= ($this->updateCustomerAccount($added_amount,$this->request['user'])) ? "Customer profile updated" : "Customer profile not updaed";
            return $status;
        } catch (Exception $e) {
            return "Error : " .$e->getMessage() . " in line : " . $e->getLine() ;
        }
    }

    public function debitAmount() : string {
        try{
            $status = '';
            $present_amount = $this->getAmountByUser($this->request['user']);
            $current_amount = $present_amount - $this->request['amount'];
            if ($current_amount <= 0) {
                return "Transaction Failed : Bank balace is not sufficient";
            }
            if ($this->inserttransactionDetails($this->request['user'],$this->request['amount'],$current_amount,'debit')) {
                $status = "Transaction successful,";
            } else {
                return "Transaction not successful";
            }
            $status .= ($this->updateCustomerAccount($current_amount,$this->request['user'])) ? "Customer profile updated" : "Customer profile not updated";
            return $status;
        } catch (Exception $e) {
            return "Error : " .$e->getMessage() . " in line : " . $e->getLine() ;
        }
    }

    public function moneyTransfer() : string {
        try{
            $present_amount_user = $this->getAmountByUser($this->request['user']);
            $current_amount_user = $present_amount_user - $this->request['amount'];
            $present_amount_receiver = $this->getAmountByUser($this->request['receiver_name']);
            $current_amount_reveiver = $present_amount_receiver + $this->request['amount'];
            if ($current_amount_user <= 0) {
                return "Transaction Failed : Bank balace is not sufficient";
            }
            if (!$this->checkReceiverCredential()) return "Please check the reveiver details";
            $this->inserttransactionDetails($this->request['user'],$this->request['amount'],$current_amount_user,'debit');
            $this->inserttransactionDetails($this->request['receiver_name'],$this->request['amount'],$current_amount_reveiver,'credit');
            $this->updateCustomerAccount($current_amount_user,$this->request['user']);
            $this->updateCustomerAccount($current_amount_reveiver,$this->request['receiver_name']);
            return "Amount successfully transfer";
        } catch (Exception $e) {
            return "Error : " . $e->getMessage() . " on line : " . $e->getLine();
        }
    }

    public function seeAlltransactionByCustomer() {
        try {
            $tdata = '';
            $stmt = $this->pdo->prepare('SELECT * from `transaction` WHERE `name`=?');
            $stmt->execute([$this->request['user']]);
            if ($stmt->rowCount() > 0) {
                $all_record = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($all_record as $transaction) {
                    $tdata .= $this->maketable($transaction);
                }
                return $tdata;
            } else {
                return '<tr><td colspan = 6 style = '."text-align:center;font-size:120%".'>No transaction process from your account yet</td></tr>';
            }
        } catch (Exception $e) {
            return '<tr><td colspan = 6 style = '."text-align:center;font-size:120%".'> Error : ' .$e->getMessage() .'Line :'. $e->getLine() . '</td></tr>';
        }
    }
    public function getAmountByUser($name) {
        $stmt = $this->pdo->prepare("SELECT `amount` FROM `customer` WHERE `username`=? limit 1");
        $stmt->execute([$name]);
        $present_amount = $stmt->fetch(PDO::FETCH_COLUMN);
        return $present_amount;
    }
 
    public function inserttransactionDetails($name,$amount,$update_amount,$flag) {
        $transaction_id = $this->randomNumber();
        $stmt = $this->pdo->prepare("INSERT INTO `transaction`(`tran_id`,`name`,`debit`,`credit`,`amount`) VALUES (?,?,?,?,?)");
        if ($flag == 'debit') {
            return ($stmt->execute([$transaction_id,$name,$amount,'',$update_amount])) ? true : false;
        } else {
            return ($stmt->execute([$transaction_id,$name,'',$amount,$update_amount])) ? true : false;
        }
        
    }

    public function updateCustomerAccount($amount,$name) {
        try {
            $stmt = $this->pdo->prepare("UPDATE `customer` set `amount`=? WHERE `username`=?");
            return $stmt->execute([$amount,$name]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function checkReceiverCredential() {
        $stmt = $this->pdo->prepare("SELECT * FROM `customer` WHERE `username`=? and `id`=?");
        $stmt->execute([$this->request['receiver_name'],$this->request['unique_id']]);
        return ($stmt->rowCount() > 0) ? true : false;
    }

    public function randomNumber($length=10) {
        $transaction_id = '';
        $pool = array_merge(range(0,9),range('A','Z')); 
        for($i=0; $i < $length; $i++) {
            $transaction_id .= $pool[mt_rand(0,count($pool)-1)];
        }
        return $transaction_id;
    }

    public function maketable($tdata) {
        $row = '';
        $row .= '<td >' . $tdata['tran_id'] . '</td>';
        $row .= '<td >' . $tdata['name'] . '</td>';
        $row .= '<td >' . $tdata['date'] . '</td>';
        $row .= '<td >' . $tdata['debit'] . '</td>';
        $row .= '<td >' . $tdata['credit'] . '</td>';
        $row .= '<td >' . $tdata['amount'] . '</td>';
        $tablercd = '<tr>'.$row.'</tr>';
        return $tablercd;
    }
}

?>