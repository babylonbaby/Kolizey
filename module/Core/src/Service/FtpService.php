<?php
namespace Core\Service;

use Zend\ServiceManager\ServiceManager;
use Core\ServiceManager\ServiceLocatorAwareInterface;
use Core\ServiceManager\ServiceLocatorAwareTrait;

class FtpService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    private $host = null;
    private $username = null;
    private $password = null;

    private $srcDir = null;
    private $destDir = null;

    protected $connection = null;
    protected $loginResult = false;

    protected $fileFilter = null;

    protected $debug = false;

    protected $syncResult = Array();

    protected $startDateTime = false;

    /**
     * @return null
     */
    public function getFileFilter()
    {
        return $this->fileFilter;
    }

    /**
     * @param null $fileFilter
     */
    public function setFileFilter($fileFilter)
    {
        $this->fileFilter = $fileFilter;
    }

    /**
     * @return null
     */
    public function getDestDir()
    {
        return $this->destDir;
    }

    /**
     * @param $destDir
     * @return $this
     */
    public function setDestDir($destDir)
    {
        if($destDir){
            $this->destDir = rtrim($destDir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.uniqid().DIRECTORY_SEPARATOR;
            if(!is_dir($this->destDir)) {
                mkdir($this->destDir, 0755, true);
            }
        }
        return $this;
    }

    /**
     * @return null
     */
    public function getSrcDir()
    {
        return $this->srcDir;
    }

    /**
     * @param $srcDir
     * @return $this
     */
    public function setSrcDir($srcDir)
    {
        if($srcDir){
            $this->srcDir = rtrim($srcDir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        }
        return $this;
    }

    /**
     * @param $showLogs
     * @return array
     * @throws
     */
    public function sync($showLogs = false){
        if($this->debug && $this->syncResult){
            return $this->syncResult;
        }
        if(!$this->getSrcDir()||!$this->getDestDir()) {
            return false;
        }
        if(!$this->connect()->isConnected()) {
            throw \Exception('Cannot connect');
        }
        //очищаем временную папку
        $this->cleanDir($this->getDestDir());
        $filesXml = array();
        $contents = $this->listDir($this->getSrcDir());
        if($contents){
            foreach($contents as $file){
                // проверяем необходимость загрузки и обработки данного файла $file
                if(!$this->checkingNeedFile($file)){
                    continue;
                }
                // проверяем имя файла (расширение xml.zip)
                if(preg_match("/[^\/]+\.xml\.zip$/i", $file, $fileName)){
                    if ($showLogs) echo 'Загрузка файла: ' . $this->getDestDir() . $fileName[0] . "\n";
                    // загружаем файл
                    if ($this->downloadFile($file, $this->getDestDir() . $fileName[0])) {
                        // извлекаем содержимое архива
                        if(!$this->unZip($fileName[0], $filesXml)){
                            echo 'Не удалось извлечь содержимое архива ' . $this->getDestDir() . $fileName[0] . "\n";
                        }
                    } else {
                        echo 'Не удалось завершить операцию c адресом '.$file."\n";
                    }
                }
            }
        }
        $this->disconnect();
        return $filesXml;
    }

    /**
     * @param $host
     * @param $username
     * @param $password
     * @return $this
     */
    public function init($host, $username, $password){
        $this->host     = $host;
        $this->username = $username;
        $this->password = $password;
        if(!$this->destDir) {
            $this->setDestDir('.' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'ftp'. DIRECTORY_SEPARATOR);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function removeDest() {
        if($this->debug){
            return $this;
        }
        if(is_dir($this->destDir)) {
            $this->cleanDir($this->destDir);
            rmdir($this->destDir);
        }
        return $this;
    }
    /**
     * @return null|resource
     */
    private function connect(){
        if(is_null($this->connection) && !$this->debug) {
            $this->connection = ftp_connect($this->host);
            $this->loginResult = ftp_login($this->connection, $this->username, $this->password);
            ftp_pasv ($this->connection, true);
        }
        return $this;
    }

    /**
     * @return bool
     */
    private function isConnected(){
        if($this->debug) return true;
        return !is_null($this->connection)&&$this->loginResult;
    }

    /**
     * @return $this
     */
    private function disconnect(){
        if($this->debug) return $this;
        ftp_close($this->connection);
        return $this;
    }

    private function reConnect(){
        if($this->isConnected()){
            ftp_close($this->connection);
            $this->connection = ftp_connect($this->host);
            $this->loginResult = ftp_login($this->connection, $this->username, $this->password);
            ftp_pasv($this->connection, true);
        }
        return $this;
    }

    /**
     * @param $dir
     * @return array
     */
    private function listDir($dir) {
        if(!$this->isConnected()) {
            $this->connect();
        }

        if (ftp_size($this->connection, rtrim($dir, '/')) == '-1') {
            // Is directory
            return ftp_nlist($this->connection, $dir);
        } else {
            // Is file
            return [rtrim($dir, '/')];
        }
    }

    /**
     * @param $dir
     */
    private function cleanDir($dir) {
        $files = glob($dir . "/*");
        if (count($files) > 0) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param boolean $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    public function setDestDirDebug($destDir)
    {
        $this->destDir = $destDir;
    }

    public function setSyncResultDebug($syncResult)
    {
        $this->syncResult = $syncResult;
    }

    public function getStartDateTime()
    {
        return $this->startDateTime;
    }


    public function setStartDateTime($startDateTime)
    {
        $this->startDateTime = $startDateTime;
    }

    /**
     * Загружает файл с использованием библиотеки curl.
     * $loadFrom - путь к файлу (относительно корня FTP)
     * $saveTo - путь для записи (сохранения файла)
     * @param $loadFrom
     * @param $saveTo
     * @return bool
     */
    public function downloadFile($loadFrom, $saveTo){
        try{
            $curl = curl_init();
            $file = fopen($saveTo, 'w');
            curl_setopt($curl, CURLOPT_URL, "ftp://" . $this->host . $loadFrom);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FILE, $file);
            curl_setopt($curl, CURLOPT_USERPWD, $this->username . ":" . $this->password);
            curl_exec($curl);
            curl_close($curl);
            fclose($file);
        }catch (\Exception $e){
            return false;
        }
        return true;
    }

    public function unZip($fileName, &$filesXml){
        try{
            $zip = new \ZipArchive();
            if ($zip->open($this->getDestDir() . $fileName) === true) {
                $zip->extractTo($this->getDestDir());
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    $fileinfo = pathinfo($filename);
                    $filesXml[] = $this->getDestDir() . $fileinfo['basename'];
                }
                $zip->close();
            }
        }catch (\Exception $e){
            return false;
        }
        return true;
    }

    /**
     * Проверяем необходимость загрузки и обработки данного файла $file
     * @param $file
     * @return bool
     */
    protected function checkingNeedFile($file){
        // проверяем, если определен фильтр для файла
        if($this->getFileFilter() && !preg_match($this->getFileFilter(), $file)){
            return false;
        }
        // проверяем дату, если она определена
        $matches = array();
        if($this->getStartDateTime() && preg_match("/\D_([\d]{8})[\d]+_/i", $file, $matches)){
            if(isset($matches[1])){
                $startDate = \DateTime::createFromFormat("Ymd", $matches[1]);
                if($startDate < $this->getStartDateTime()){
                    return false;
                }
            }else{
                return false;
            }
        }
        return true;
    }
}