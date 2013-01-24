<?php
namespace LWddd;
use \lw_view as lw_view;

class commandLogsHandler 
{
    private $db;
    private $request;
    private $pageSize = 10;

    public function __construct($db, $request) 
    {
        $this->db = $db;
        $this->request = $request;
    }
    
    /**
     * Overrides the default pageSize of 10
     * @param int $size
     */
    public function setPageSize($size)
    {
        $this->pageSize = intval($size);
    }
    
    /**
     * Creation of lw_command_log table
     * @return boolean
     * @throws \Exception
     */
    public function createLogTable()
    {
        $create_statement = "CREATE TABLE IF NOT EXISTS lw_command_log (
                              id int(11) NOT NULL AUTO_INCREMENT,
                              project varchar(255) NOT NULL,
                              domain varchar(22) NOT NULL,
                              statement longtext NOT NULL,
                              date varchar(20) NOT NULL,
                              PRIMARY KEY (id)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ; ";
        
        if(!$this->db->tableExists($this->db->gt("lw_command_log"))){
            $this->db->setStatement($create_statement);
            $ok = $this->db->pdbquery();
            if(!$ok){
                throw new \Exception('ERROR: Tablecreation "lw_command_log" '); 
            }else{
                return true;
            }
        }
        return true;
    }
    
    /**
     * Add a new log entry
     * @param type $array
     * @return boolean
     * @throws \Exception
     */
    public function addLog($array)
    {
        $this->db->setStatement("INSERT INTO t:lw_command_log ( project, domain, statement, date ) VALUES ( :project, :domain, :statement, :date ) ");
        $this->db->bindParameter("project", "s", $array["project"]);
        $this->db->bindParameter("domain", "s", $array["domain"]);
        $this->db->bindParameter("statement", "s", base64_encode($array["statement"]));
        #$this->db->bindParameter("date", "i", microtime());
        $this->db->bindParameter("date", "i", date("YmdHis"));
        
        $ok = $this->db->pdbquery();
        if(!$ok){
            throw new \Exception("ERROR: lw_command_log -> addLog");
            return false;
        }
        return true;
    }
    
    /**
     * Load all loged entries with empty $filterArray,
     * Load all loged entries with certain conditions which are set in the $filterArray,
     * If $count = true => Get the count of entries in reference to the $filterArray,
     * If $limitOptions set => Load entries in reference to $filterArray with row start and amount
     * @param array $filterArray
     * @param bool $count
     * @param array $limitOptions
     * @return array
     */
    public function getLogsByFilter($filterArray, $count = false, $limitOptions = false)
    {
        /**
         * $filterArray = array(
         *      "project"   =>  $project,
         *      "domain"    =>  $domain,
         *      "start_date"=>  $start_date,
         *      "end_date"  =>  $end_date
         * );
         * 
         */
        if($count === true){
            $add = " COUNT(*) ";
        }else{
            $add = " * ";
        }

        if(array_key_exists("start_date", $filterArray) && array_key_exists("end_date", $filterArray)){
            $datePeriod = " AND date >= ".$filterArray["start_date"]." AND date <= ".$filterArray["end_date"];            
        }elseif(array_key_exists("start_date", $filterArray)){
            $datePeriod = " AND date >= ".$filterArray["start_date"];            
        }elseif (array_key_exists("end_date", $filterArray)){
            $datePeriod = " AND date <= ".$filterArray["end_date"];            
        }
        
        if(empty($filterArray)){
            $this->db->setStatement("SELECT ". $add ." FROM t:lw_command_log " );
        }else{
            
            foreach($filterArray as $key => $value){
                if($key != "start_date" && $key != "end_date"){
                    $filterToSql.= " ".$key." = :".$key." AND";
                }
            }
            $filterToSql = substr($filterToSql, 0, strlen($filterToSql) - 3);
            $this->db->setStatement("SELECT ". $add ." FROM t:lw_command_log WHERE ".$filterToSql.$datePeriod );
            foreach($filterArray as $key => $value){
                $this->db->bindParameter($key, "s", $value);
            }
        }
        
        if($limitOptions){
            return $this->db->pselect($limitOptions["start"],$limitOptions["amount"]);
        }
        return $this->db->pselect();
    }
    
    /**
     * Count all entries which are selcted with the $filterArray
     * @param array $filterArray
     * @return int
     */
    public function getLogsCount($filterArray)
    {
        $result = $this->getLogsByFilter($filterArray, true);
        return intval($result[0]["COUNT(*)"]);
    }
    
    /**
     * Returns the constructed and filled table with loaded log entries
     * @param array $filterArray
     * @return string
     */
    public function showLogs($filterArray)
    {
        $baseUrl = \lw_page::getInstance()->getUrl();
        
        $count = $this->getLogsCount($filterArray);
        $view = new lw_view(dirname(__FILE__).'/templates/logsTable.tpl.phtml'); 
        $view->pageSize = $this->pageSize;
  
        $view->pageCount = intval($count/$this->pageSize);
                
        if($this->request->getInt("page")){
            $page = $this->request->getInt("page");
            $view->page = $page;
            $start = $page * $this->pageSize;
            $view->next = $baseUrl."&page=".($page + 1);
            $view->prev = $baseUrl."&page=".($page - 1);
        }else{
            $view->page = 1;
            $start = 0;
            $view->next = $baseUrl."&page=2";
        }
        
        $view->logs = $this->getLogsByFilter($filterArray, false, array("start" => $start, "amount" => $this->pageSize));

        return $view->render();
    }
    
    /**
     * Execute the loaded sql statements
     * @param array $logs
     */
    public function executeLogs($logs)
    {
        foreach($logs as $log){
            $this->db->setStatement(base64_decode($log["statement"]));
            $this->db->pdbquery();
        }
    }
}