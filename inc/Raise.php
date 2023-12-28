<?php

namespace inc;

class Raise {

    /**
     *
     * @var String ISO 639-1 Language Code 
     */
    public $lang = '';

    /**
     *
     * @var String
     */
    private $baseUrl = '';

    /**
     *
     * @var String
     */
    public $controllerPath = '\src\controllers\\';

    /**
     * Initial Functions related 
     */
    public function initApp() {
        $this->initSession();
        $this->siteLang();
        $this->baseUrl = parse_url(BASEURL);
       // $this->initCSRF();
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

        $this->lang = (isset($_SESSION[SessionName.'_bo_lang'])) ? $_SESSION[SessionName.'_bo_lang'] : 'en';
        if (trim($forceLang) != '') {
            $this->lang = Raise::cleanMe($forceLang);
        }
        
        $_COOKIE['language']                = $this->lang;
        $_SESSION[SessionName.'_bo_lang']   = $this->lang;
        setlocale(LC_ALL, $this->lang);       
    }

    /**
     * 
     * @return Array
     */
    public static function getLang() {
        $langs = [];
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
            if (count($lang_parse[1])) {
                $langs = array_combine($lang_parse[1], $lang_parse[4]);
                foreach ($langs as $lang => $val) {
                    if ($val === '')
                        $langs[$lang] = 1;
                }
                arsort($langs, SORT_NUMERIC);
            }
        }
        return $langs;
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
        $reqURI  = explode('/', $mvcPath[0]);
        $reqUrl  = array_filter($reqURI);
        $params  = self::params();
        if ((count($reqUrl) === 1 || count($reqUrl) === 0 ) && count($params) > 0 && array_key_exists('mvc', $params) && array_key_exists('defaults', $params['mvc']) && array_key_exists('controller', $params['mvc']['defaults'])) {
            $reqUrl[0] = (count($reqUrl) === 1) ? $reqUrl[0] : $params['mvc']['defaults']['controller'];
            $reqUrl[1] = array_key_exists('action', $params['mvc']['defaults']) ? $params['mvc']['defaults']['action'] : 'index';
        }
        return $reqUrl;
    }

    /**
     * 
     * @return Array $params
     */
    public static function params() {
        return $GLOBALS['raiseParams'];
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
     * @param String $cat
     * @param String $key
     * @param Array $params
     * @return type
     * @throws \Exception
     */
    public static function t($cat = ' ', $key = '', $params = []) {

        $langPath = BASEPATH . '/src/i18n/' . $_SESSION[SessionName.'_bo_lang'] . '/' . $cat . '.php';
        
        if (file_exists($langPath)) {
            $msgs = include $langPath;
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
            throw new \Exception('Language Category File ' .$_SESSION[SessionName.'_bo_lang']. $cat . ' Not Found!');
        }
    }

    /**
     *
     * @param String $cats
     * @param String $lang
     * @return Array
     */
    public static function i18n($cats = ['app'], $lang = '') {
        error_log('i18n');
        $retLang = [];
        $slang = (empty($lang)) ? $_SESSION[SessionName.'_bo_lang'] : $lang;
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
     * @param String $input
     * @return Mixed
     */
    public static function cleanMe($input) {
        $inputStiped = stripslashes($input);
        $inputHtml = htmlspecialchars($inputStiped, ENT_IGNORE, 'utf-8');
        return strip_tags($inputHtml);
    }

    /**
     * 
     * @param array $post
     * @return array
     */
    public static function cleanAllValue($post) {
        $newArray = array();
        foreach ($post as $key => $val) {
            $newArray[$key] = Raise::cleanMe($val);
        }
        return $newArray;
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
    public static function CSRF($name = '_csrf_token') {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : '';
    }

    /**
     * Method to handle the session hijacking
     */
    public function safeSession() {
        $remoteAddr = isset($_SESSION['REMOTE_ADDR']) ? $_SESSION['REMOTE_ADDR'] : '';
        $serverAddr = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (isset($remoteAddr) && !empty($remoteAddr)) {
            if ($remoteAddr != sha1($serverAddr . $userAgent)) {
                $_SESSION['PHPSESSID'] = rand(1, 1000);
                unset($_SESSION['REMOTE_ADDR']);
                header('HTTP/1.0 403 Forbidden');
                die('Session Crash.');
            }
        } else {
            $_SESSION['REMOTE_ADDR'] = sha1($serverAddr . $userAgent);
        }
    }

}
