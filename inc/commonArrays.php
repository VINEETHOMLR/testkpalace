<?php

namespace inc;

class commonArrays{

    public static function getArrays(){
    
        $var["transactionArray"] = self::transactionTypes();

        $var["wallets"]          = self::wallets();

        $var['announcementArr']  = self::getAnnoucementArrays();

        $var['announcementArr']  = self::getAnnoucementArrays();
    
        $var['getServiceArray']  = self::getServiceArray();

        $var["creditArray"]      = [0=>"Credit",1=>"Debit"];

        $var["userTypeArray"]    = [1=>'Normal User',2=>'Free User'];
        
        $var['userStatusArr']    = [0=>"Active",1=>"Blocked"];

        $var['depositStatusArr'] = ['Pending','Processing','Approved','Rejected'];
    
        $var['WithdrawStatusArr']   = [0 => "New", 1=>"Processing", 2=>"Approved", 3=>"Rejected",4 => 'Failed',6=>'Cancelled'];
    
        $var['WithdrawusdtType']    = ['Pending','Processing','Approved','Rejected'];
    
        $var['WithdrawusdtType']    = [1=>'ERC20',2=>'TRC20'];
    
        $var['WithdrawtransArray']  = [1 => 'Node', 2 => 'Internal Transaction'];
    
        $var['WithdrawwalletType']  = [1=>'Demo Wallet 1',2=>'Demo Wallet 2'];
    
        $var['WithdrawBatchStatusArr']   = [0 => 'New', 5 => 'Pending', 1 => 'Processing', 2 => 'Approved', 3 => 'Rejected', 4 => 'Failed'];
      
        $var['BatchStatusArr']      = [0 => 'Pending', 1 => 'Batch Request Sent', 2 => 'Finished', 3 => 'Failed', 4 => 'Restarted'];
    
        $var['ethStatusArr']        = [0=>'Pending', 1=> 'Sucess', 2=> 'Failed'];

        $var['kyc']['type']         = [1=>'ID',2=>"Passport"];
        $var['kyc']['status']       = [1=>'Pending',2=>"Approved",3=>"Rejected"];
        $var['roleArr']             = ['1'=>'Floor Staff','2'=>'Inventory Admin','3'=>'Management Admin','4'=>'HR Admin','5'=>'Manager','6'=>'Room Tablet'];
    
        return $var;
    }

    private static function transactionTypes(){

        return [
             31 => "Purchase Cash Credit",
             32 => "Cash Credit Payment",
             33 => "Purchase Bonus Credit",
             34 => "Bonus Credit Payment",
             35 => "Total Spend Credit",
             36 => "Total Spend Debit",
        ];
    }

    private static function getServiceArray(){

        return[
            1    => 'Admin List',
            2    => 'Admin Activity Log',
            3    => 'Admin Service Group',
            4    => 'Create Admin',
            5    => 'Update Admin',
            6    => 'Delete Admin',
            7    => 'Start/Stop Maintenance',
            //8    => 'Whitelisp IP',
            //9    => 'General Settings',
            10   => 'Export List',

                11   => 'Customer List',
                12   => 'Customer Status',
                13   => 'Customer Profile Updation',
                14   => 'Customer Password Updation',
                15   => 'Customer Gallery',
                16   => 'Customer Alcohol List',

                17   => 'Alcohol Category List',
                18   => 'Alcohol List',
                
                19  => 'Cash Credit',
                20  => 'Bonus Credit',
                21  => 'Total Spend',

                22  => 'Memo',
                23  => 'Promotion',
                24  => 'Package',
                25  => 'Customer Alcohol Create',
                26  => 'Customer Alcohol Edit',
                27  => 'Customer Alcohol Delete',
                28   => 'Alcohol Category Create',
                29   => 'Alcohol Category Edit',
                30   => 'Alcohol Category Delete',
                31   => 'Alcohol Inventory Create',
                32   => 'Alcohol Inventory Edit',
                33   => 'Alcohol Inventory Delete',
                34   => 'Promotion Create',
                35   => 'Promotion Edit',
                36   => 'Promotion Delete',
                37   => 'Package Create',
                38   => 'Package Edit',
                39   => 'Package Delete',
                40   => 'Memo Create',
                41   => 'Memo Edit',
                42   => 'Memo Delete',
                43   => 'Customer Gallery Create',
                44   => 'Customer Gallery Edit',
                45   => 'Customer Gallery Delete',






          
      
        ];

    }

    private static function wallets(){

        return array(

            1 => ['label'             => 'Cash Credit',
                  'table_column_name' => 'cash_wallet',
                  'history_table_name'=> 'cash_credit_log',
                  'is_credit_enabled' => 1,
                  'is_debit_enabled'  => 1,
                  'is_hidden'         => 0,
                  'decimal_limit'     => 8,
                  'transaction_types' => [31,32],
                 ],

            2 => ['label'             => 'Bonus Credit',
                  'table_column_name' => 'bonus_wallet',
                  'history_table_name' => 'bonus_credit_log',
                  'is_credit_enabled' => 1,
                  'is_debit_enabled'  => 1,
                  'is_hidden'         => 0,
                  'decimal_limit'     => 8,
                  'transaction_types' => [33,34],
                 ],

                 3 => ['label'        => 'Total Spend',
                  'table_column_name' => 'total_spend',
                  'history_table_name' => 'total_spend_log',
                  'is_credit_enabled' => 1,
                  'is_debit_enabled'  => 1,
                  'is_hidden'         => 0,
                  'decimal_limit'     => 8,
                  'transaction_types' => [35,36],
                 ]
        );
    }
  
    private static function getAnnoucementArrays(){
    
        $var['annoStatusArr']  = array(

            0=>"Active",
            1=>"Inactive",
          //  2=>"Disabled"
        );

        $var['memoStatusArr']  = array(

            0=>"Hidden",
            1=>"Published",
            2=>"Disabled"
           
        );
    
        $var['fileType']  = array(

            1=>"Image/PDF",
            2=>"Video",
        );
        
        $var['Categorytype']  = array(

            1=>"PDF",
            2=>"Image",
            3=>"Video",
        );

         $var['coinStatusArr']  = array(

            0=>"Inactive",
            1=>"Active",
            2=>"Disabled"
        );

        return $var;
    }
}

?>
