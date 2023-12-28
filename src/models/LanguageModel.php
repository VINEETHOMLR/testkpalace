<?php

namespace src\models;

use framework\lib\Database;
use framework\inc\Root;
use framework\lib\Router;
use framework\lib\Helper as H;

/**
* @property int(11) $id
* @property varchar(40) $lang_name
* @property varchar(7) $lang_code
* @property tinyint(4) $status
**/

class LanguageModel extends Database
{

    protected $pk = "id";

	public function __construct($db = 'db')
	{
		parent::__construct(Root::db());
		$this->tableName = 'language';
        $this->assignAttrs();
	}


    /**
     * 
     * @return Array
     */
    public static function attrs() {
        return ["id","lang_name", "lang_code", "status"];
    }

    /**
     * 
     * @return $this
     */
    public function assignAttrs($attrs = []) {
        $isExternal = !empty($attrs);
        foreach (($isExternal ? $attrs : self::attrs()) as $eAttr => $attr) {
            $aAttr = $isExternal ? $eAttr : $attr;
            $this->{$aAttr} = $isExternal ? $attr : (Router::post($attr) !== "" ? Router::post($attr) : "");
        }
        return $this;
    }

    /**
     * 
     * @param INT $pk
     */
    public function findByPK($pk) {
        $dtAry = parent::findByPK($pk);
        foreach ($dtAry as $attr => $val) {
            $this->{$attr} = $val;
        }
        return $this;
    }

    /**
     * createRecord
     *
     * @param array $data
     * @return mixed $collection
     */
    public function createRecord($data)
    {
        $this->assignAttrs($data);
        return $this->save();
    }

    /**
     * delete record
     *
     * @param int $id
     * @return boolean $status
     */
    public function deleteRecord($params)
    {
        if( !isset($params['id']) || !is_numeric($params['id'])){
            return 'id_invalid';
        }

        $marquee = $this->findByPK($params['id']);
        if(empty($marquee->lang_code)){
            return 'not_found';
        }

        if($marquee->deleteByPK($params['id']) === true){
            return true;
        }
        return 'delete_fail';
    }

    /**
     * @param array $ip,string $type
     * @return mixed $collection
     */
	public function getAll($ip, $type = 'row')
    {
        $sql = $where_str = $select = '';
        $offset = isset($ip['start']) ?? 0;
        $limit  = isset($ip['length']) ?? 0;
        $where_str_array = array();

        if (!empty($ip['lang_code'])) {
            $where_str_array[] = 'lang_code=\''.$ip['lang_code'].'\'';
        }
        if (isset($ip['status']) && $ip['status'] != '') {
            $where_str_array[] = 'status=\''.$ip['status'].'\'';
        }

        $select = '*';
        if (!empty($ip['select'])) {
            if (is_array($ip['select'])) {
                $select = implode(',', $ip['select']);
            } else {
                $select = $ip['select'];
            }
        }

        $where_str = '1';
        if (!empty($where_str_array)) {
            $where_str = implode(' AND ', $where_str_array);
        }

        $sql = 'SELECT '.$select.' FROM '.$this->tableName.' WHERE '.$where_str.' ';

        if (!empty($ip['group_by_str'])) {
            $sql .= ' '.$ip['group_by_str'].' ';
        }

        if (!empty($ip['sort_column']) && !empty($ip['sort_order'])) {
            $sql .= ' ORDER BY '.$ip['sort_column'].' '.$ip['sort_order'].' ';
        }

        if(!empty($limit)){
            $sql .= ' LIMIT '.$offset.','.$limit;
        }

        return $this->callsql($sql, $type);
    }

    /**
     * @return mixed $collection
     */
    public function getLangData(){
        $lang = [];
        $rows = $this->callSql("SELECT lang_name,lang_code FROM $this->tableName WHERE status = 1","rows");

        if(!empty($rows)){
          foreach ($rows as $key => $value) {
              $lang[$value['lang_code']] = $value['lang_name'];
          }
        }

        return $lang;
    }
}

?>