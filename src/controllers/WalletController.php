<?php

namespace src\controllers;

use inc\Controller;
use src\models\WalletHistory;
use src\lib\Router;
use src\lib\Pagination;
use inc\commonArrays;
use inc\Raise;

class WalletController extends Controller {

    public function __construct() {

        parent::__construct();

        $this->mdl   = (new WalletHistory);
        $this->pag   = new Pagination(new WalletHistory(),''); 
        

        $this->creditType = $this->systemArrays['creditArray'];
        $this->transactionArray = $this->systemArrays['transactionArray'];
      
    }

    public function actionCashcredit() {


        $this->checkPageAccess(17);

        $info = $this->getHistoryData('cash_credit_log');

        $pageInfo['title'] = 'Cash Credit'; 
        $pageInfo['url'] = 'Wallet/Cashcredit/';
        $pageInfo['menu'] = 'Cashcredit';
        $pageInfo['tablename'] = 'cash_credit_log';
        $pageInfo['filename'] = 'cash_credit_log';
        $pageInfo['total_text'] = 'Total Cash Credit';

        return $this->render('wallet/history',['input'=>$info['filter'], 'data' => $info['data'],'pageInfo'=>$pageInfo]);
    }
   
    public function actionBonuscredit() {

        $this->checkPageAccess(18);

        $info = $this->getHistoryData('bonus_credit_log');

        $pageInfo['title'] = 'Bonuscredit'; 
        $pageInfo['url'] = 'Wallet/Bonuscredit/';
        $pageInfo['menu'] = 'Bonuscredit';
        $pageInfo['tablename'] = 'bonus_credit_log';
        $pageInfo['filename'] = 'bonus_credit_log';
        $pageInfo['total_text'] = 'Total Bonus Credit';

        return $this->render('wallet/history',['input'=>$info['filter'],'data' => $info['data'],'pageInfo'=>$pageInfo]);
    }
    
    public function actionTotalSpend() {

        $this->checkPageAccess(19);

        $info = $this->getHistoryData('total_spend_log');

        $pageInfo['title'] = 'Total Spend'; 
        $pageInfo['url'] = 'Wallet/TotalSpend/';
        $pageInfo['menu'] = 'TotalSpend';
        $pageInfo['tablename'] = 'total_spend_log';
        $pageInfo['filename'] = 'total_spend_log';
        $pageInfo['total_text'] = 'Total Amount';

        return $this->render('wallet/history',['input'=>$info['filter'],'data' => $info['data'],'pageInfo'=>$pageInfo]);
    }

   
    public function getInputs() {
        
        $input = [];
        $input['datefrom'] = $this->cleanMe(Router::post('datefrom')); 
        $input['dateto'] = $this->cleanMe(Router::post('dateto'));
        $input['creditType'] = $this->cleanMe(Router::post('creditType')); 
        $input['txn_type'] = $this->cleanMe(Router::post('txn_type')); 
        $input['user_id'] = $this->cleanMe(Router::post('user_id')); 
        $input['page'] = $this->cleanMe(Router::post('page')) ; 
        $input['load'] = empty($input['page']) ? 0 : 1 ;

        return $input;
    }

    public function getHistoryData($wallet) {

        $filter = $this->getInputs();
        $filter['tableName'] = $wallet;

        
        $data = $this->mdl->getHistory($filter);

        if( ! empty($filter['user_id'])){
            $data['s_username']    = $this->mdl->getusername($filter['user_id']);
        }

        $onclick = "onclick=pageHistory('".$filter['datefrom']."','".$filter['dateto']."','".$filter['user_id']."','".$filter['txn_type']."','".$filter['creditType']."','***')";

        $data['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        $ret_data = ['filter'=>$filter,'data'=>$data];

        return $ret_data;
    }

   

    public function actionExport() {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');

        $wallet = $this->cleanMe(Router::post('tablename')); 
        $filename = $this->cleanMe(Router::post('filename')); 

        $datefrom = $this->cleanMe(Router::post('datefrom')); 
        $dateto   = $this->cleanMe(Router::post('dateto'));
        $user  = $this->cleanMe(Router::post('user_id'));

        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;

        $filter = $this->getInputs();
        $filter['export'] = "export";
        $filter['tableName'] = $wallet;

        $data = $this->mdl->getHistory($filter);

        $csv = "User ID, Username, Credit Type, Transaction Type, Amount,Before Balance,After Balance,Transaction Date,Remarks  \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";
        foreach ($data['data'] as $his) {  

            $html.= $his['uniqueid'].','.$his['username'].','.$his['credit_type'].','.$his['txn_type'].','.$his['value'].','.$his['before_bal'].','.$his['after_bal'].','.$his['txn_date'].','.$his['remarks']."\n"; //Append data to csv

        }
        if(!empty($html)){
            fwrite($csv_handler,$html);
        }

        fclose($csv_handler);

        $act="Admin export file -".$filename." history";
        $log_data = array(
            "user" => $user,
            "datefrom" => $datefrom,
            "dateto" => $dateto,
            "export" => $filename." history"
            );

        $logdata = json_encode($log_data,JSON_UNESCAPED_UNICODE);
        $this->mdl->adminActivityLog($act,$logdata);

        $download = '<a href="'.BASEURL.'web/upload/'.$filename_nw.'" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="'.BASEURL.'web/upload/'.$filename_nw.'" style="float:right;">Download</button></a>';

        return $this->sendMessage('success',$download);
    }

}
