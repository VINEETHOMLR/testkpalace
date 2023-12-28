<?php

namespace inc;

/**
 * Core MVC GTR
 *
 */
class Root {

    /**
     *
     * @var String ISO 639-1 Language Code
     */
    public $lang = '';

    /**
     *
     * @var String
     */
    public $cookieLang = '';

    /**
     *
     * @var String
     */
    private $baseUrl = '';

    private $controllerNameMappings = [];

    /**
     * Initial Functions related
     */
    public function initApp() {
        $this->initSession();
        $this->siteLang();
        $this->baseUrl = parse_url(BASEURL);
        //$this->initCSRF();
        $this->parseURI();
    }

    /**
     * Method to initiate the session 
     */
    public function initSession() {
        ini_set("session.cookie_httponly", 1);
        session_start();
        $sesParams = session_get_cookie_params();
        setcookie('PHPSESSID', session_id(), 0, $sesParams["path"], $sesParams["domain"], false, true);
        ob_start();
    }

    /**
     *
     * @param String $forceLang
     * @return String the Language
     */
    public function siteLang($forceLang = '') {
        
        $this->lang   = (isset($_SESSION['SITE_LANG'])) ? $_SESSION['SITE_LANG'] : 'en';

        if (trim($forceLang) != '') {
            $this->lang = Root::cleanMe($forceLang);
        }
        $this->cookieLang = $this->lang;
        $_COOKIE['language']   = $this->cookieLang;
        $_SESSION['SITE_LANG'] = $this->lang;
    }

    /**
     *
     * @throws \Exception
     */
    private function parseURI() {
        $req = $_SERVER['REQUEST_URI'];
        $reqUrl = $this->extractURL($req);
        $ctrlCount = $this->baseUrl['path'] !== '/' ? 2 : 1;
        try {
            if (count($reqUrl) >= $ctrlCount && array_key_exists('1', $reqUrl) && trim($reqUrl[1]) !== '') { 
                $ctlrDir = '\src\controllers\\';
                $ctrlName = array_key_exists('0', $reqUrl) ? ucfirst($reqUrl[0]) : 'Index';
                if(array_key_exists($ctrlName,$this->controllerNameMappings)){
                    $ctrlName  = $this->controllerNameMappings[$ctrlName];
                }

                $ctlr = $ctlrDir . $ctrlName . 'Controller';
                $GLOBALS['raiseParams']['controller'] = (strtolower($ctrlName));
                $this->parseAction($ctlr, array_slice($reqUrl, 1));
            } else {
                http_response_code(404);
                throw new \Exception('Controller Not Found!', 404);
            }
        } catch (\Exception $ex) {
            http_response_code(404);
            throw new \Exception($ex->getMessage(), 404);
        }
    }

    /**
     *
     * @param RequestString $req
     * @return String
     */
    private function extractURL($req) {
        $reqPath = str_replace(array_key_exists('path', $this->baseUrl) && $this->baseUrl['path'] !== '/' ? $this->baseUrl['path'] : '', '', $req);
        $mvcPath = explode('?', ltrim($reqPath, '/'));
        $reqURI = explode('/', $mvcPath[0]);
        $reqUrl = array_filter($reqURI);
        $params = self::params();
        if (count($reqUrl) <= 1 && count($params) > 0 && array_key_exists('mvc', $params) && array_key_exists('defaults', $params['mvc']) && array_key_exists('controller', $params['mvc']['defaults'])) {
            if(count($reqUrl) == 0){
                $reqUrl[0] = $params['mvc']['defaults']['controller'];
            }
            $reqUrl[1] = array_key_exists('action', $params['mvc']['defaults']) ? $params['mvc']['defaults']['action'] : 'index';
        }
        return $reqUrl;
    }

    /**
     *
     * @return Array $params
     */
    public static function params() {
        global $params;
        $GLOBALS['raiseParams'] = $params;
        return $params;
    }

    /**
     *
     * @return Array $db
     */
    public static function db() {
        global $db;
        $GLOBALS['db'] = $db;
        return $db;
    }

    /**
     *
     * @param Object $ctlr
     * @param Array $uri
     * @return Mixed
     * @throws \Exception
     */
    public static function parseAction($ctlr, $uri) {

        if (!empty($uri) && trim($uri[0])) {
            $action = 'action';
            $acCase = $action . ucfirst($uri[0]); 
            $GLOBALS['raiseParams']['action'] = (strtolower($uri[0]));
            if (class_exists($ctlr) === false) {
                storeRLog();
            }
            $ctlrIns = new $ctlr();
            if (method_exists($ctlrIns, $acCase)) {
                return $ctlrIns->$acCase();
            } else {
                storeRLog();
            }
        } else {
            throw new \Exception('Action Not Found!');
        }
    }

    /**
     * 
     * @return Mixed | boolean
     */
    public static function coreI18n($slang, $cat) {
        $langPath = BASEPATH . '/src/i18n/' . $slang . '.i18n.php';
        if (file_exists($langPath)) {
            if (array_key_exists('i18n', $GLOBALS['raiseParams'])) {
                return $GLOBALS['raiseParams']['i18n'][$cat];
            } else {
                $isIncluded = include $langPath;
                $GLOBALS['raiseParams']['i18n'] = $isIncluded;
                return $GLOBALS['raiseParams']['i18n'][$cat];
            }
        }
        return false;
    }

    /**
     *
     * @param String $cat
     * @param String $key
     * @param Array $params
     * @return type
     * @throws \Exception
     */
    public static function t($cat = 'app', $key = '', $params = [], $lang = '') {
        $slang = (empty($lang)) ? $_SESSION['SITE_LANG'] : $lang;
        $langPath = BASEPATH . '/src/i18n/' . $slang . '/' . $cat . '.php';
        if (file_exists($langPath)) {
            $isCoreI18nExists = self::coreI18n($slang, $cat);
            $msgs = ($isCoreI18nExists !== false) ? $isCoreI18nExists : include $langPath;
            if (array_key_exists($key, $msgs)) {
                $trans = $msgs[$key];
                if (count($params) > 0) {
                    foreach ($params as $pKey => $pVal) {
                        $trans = str_replace('{{' . $pKey . '}}', $pVal, $trans);
                    }
                }
                return $trans;
            } else {
                return $key;
            }
        } else {
            throw new \Exception('Language Category File ' . $cat . ' Not Found!');
        }
    }

    /**
     *
     * @param String $cat
     * @param String $lang
     * @return Array
     */
    public static function i18n($cats = ['app'], $lang = '') {
        error_log('i18n');
        $retLang = [];
        $slang = (empty($lang)) ? $_SESSION['SITE_LANG'] : $lang;
        foreach ($cats as $cat) {
            $langPath = BASEPATH . '/src/i18n/' . $slang . '/' . $cat . '.php';
            if (file_exists($langPath)) {

                $retLang[$cat] = include $langPath;
            } else {
                $retLang[$cat] = [];
            }
        }
        return $retLang;
    }

    /**
     *
     * @param String $cat
     * @param String $lang
     * @return Array
     */
    public static function i18nJson($catArray = ['app'], $lang = '') {
        error_log('i18n');
        $returnArr = [];
        $slang = (empty($lang)) ? $_SESSION['SITE_LANG'] : $lang;
        foreach ($catArray as $cat) {

            $langPath = BASEPATH . '/src/i18n/' . $slang . '/' . $cat . '.php';
            if (file_exists($langPath)) {

                $lngs = include $langPath;
                $returnArr[$cat] = $lngs;
            } else {
                return [];
            }
        }
        return addcslashes(json_encode($returnArr, JSON_UNESCAPED_UNICODE), "'");
    }

    /**
     *
     * @param String $input
     * @return Mixed
     */
    public static function cleanMe($input) {
        $input = trim($input);
        $input = htmlspecialchars($input, ENT_QUOTES, "ISO-8859-1");
        $input = stripslashes($input);
        $input = strip_tags($input);
        return $input;
    }

    /**
     *
     * @param array $post
     * @return array
     */
    public static function cleaAllValue($post) {
        $newArray = array();
        foreach ($post as $key => $val) {
            $newArray[$key] = Raise::cleanMe($val);
        }
        return $newArray;
    }

    /**
     *
     * @param String $type
     * @param String $msg
     * @param Number $flag
     * @param Boolean $isJson
     * @return Mixed
     */
    public static function sendMessage($type, $msg, $flag = 0, $isJson = false) {
        $response = json_encode(["status" => $type, "response" => $msg]);
        if ($flag == 0) {
            return $response;
        } else {
            if ($isJson) {
                header('Content-Type: application/json');
            }
            ob_flush();
            die($response);
        }
    }

    /**
     * Method to generate the CSRF Token
     */
    protected function initCSRF() {
        $token = (function_exists('mcrypt_create_iv')) ? bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)) : bin2hex(openssl_random_pseudo_bytes(32));
        $_SESSION['_csrf_token'] = $token;
        setcookie('_csrf_token', $token);
        header('X-CSRF-Token: ' . $token);
        return $_SESSION['_csrf_token'];
    }

    /**
     *
     * @return String
     */
    public static function CSRF() {
        return isset($_SESSION['_csrf_token']) ? $_SESSION['_csrf_token'] : '';
    }

}
