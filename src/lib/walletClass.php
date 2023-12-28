<?php

namespace src\lib;

use src\lib\RRedis;
use src\lib\Database;
use inc\Root;
use inc\commonArrays;

class walletClass extends Database
{
    public function __construct($db = 'db')
    {
        parent::__construct(Root::db());
        $this->rds = new RRedis();
        $this->skp = 0;
    }

    public function getBalance($walletName, $userId)
    {
        $query = "SELECT $walletName FROM customer_wallet WHERE user_id=$userId";

        $balance = $this->callSql($query, 'value');

        return $balance;
    }

    public function checkBalance($walletName, $userId, $compareAmount)
    {
        $query = "SELECT $walletName FROM customer_wallet WHERE user_id=$userId";

        $balance = $this->callSql($query, 'value');

        if ($balance >= $compareAmount)
            $result = 1;
        else
            $result = 0;

        return $result;
    }
     
    private function updateBalance($walletName, $userId, $amount, $type)
    {   
        $query = 'SELECT ' . $walletName . ' FROM customer_wallet WHERE user_id=' . $userId;

        $getBalance = $this->callSql($query, 'value');

        if ($type == 0) {
            $this->callSql('UPDATE customer_wallet SET ' . $walletName . '=' . $walletName . '+' . $amount . ' WHERE user_id="' . $userId . '"');
        } else {
            if ($getBalance > $amount) {
                $this->callSql('UPDATE customer_wallet SET ' . $walletName . '=' . $walletName . '-' . $amount . ' WHERE user_id="' . $userId . '"');
            } else {
                $this->callSql('UPDATE customer_wallet SET ' . $walletName . '=0 WHERE user_id="' . $userId . '"');
            }
        }

        $newBal = $this->callSql('SELECT ' . $walletName . ' FROM customer_wallet WHERE user_id="' . $userId . '"', 'value');

        return array($getBalance, $newBal);
    }

  

    

    public function updateWallet($userId, $creditType = 1, $transType, $amount,$walletType,$transId = 0, $doneBy = 0,$force=0,$remarks)
    {
        $walletTypes  = (new commonArrays)->getArrays()['wallets'];

        $walletName   = isset($walletTypes[$walletType])?$walletTypes[$walletType]['table_column_name']:'';
        $wallethistoryName   = isset($walletTypes[$walletType])?$walletTypes[$walletType]['history_table_name']:'';
        $newBal       = 0;
        if(!empty($walletName)){

            $response = $this->updateBalance($walletName, $userId, $amount, $creditType,$force);

            $preBal = $response[0];
            $newBal = $response[1];

            $time = time();
            $date = date('Y-m-d', $time);

            $ip = $_SERVER['REMOTE_ADDR'];

            $query = "INSERT INTO `$wallethistoryName` (`user_id`,`credit_type`,`transaction_type`,`value`,`before_bal`,`after_bal`,`createtime`,`createip`,`createid`,`transactiondate`,`remarks`) VALUES
            (:user_id,:credit_type,:transaction_type,:amount,:before_bal,:after_bal,:createtime,:createip,:createid,:transactiondate,:remarks)";

            $this->query($query);
            $this->bind(':user_id', $userId);
            $this->bind(':credit_type', $creditType);
            $this->bind(':transaction_type', $transType);
            $this->bind(':amount', $amount);
            $this->bind(':before_bal', $preBal);
            $this->bind(':after_bal', $newBal);
            $this->bind(':createtime', $time);
            $this->bind(':createip', $ip);
            $this->bind(':createid', $doneBy);
            $this->bind(':transactiondate', $date);
            $this->bind(':remarks', $remarks);

            $this->execute();
        }

        return $newBal;
    }

  }
