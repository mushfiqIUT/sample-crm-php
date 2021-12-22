<?php
require_once 'include/php/conn/db_info.php';

class dbConn{
    protected static $db = null;
    
    private function __construct() {
        try {
            global $dbname;
            global $host;
            
            //log_error("dbConn __construct(): pgsql:dbname=".$dbname.";host=".$host);
            //.";user=".$_SESSION['user'].";pass=".$_SESSION['pass']
            self::$db = new PDO("pgsql:dbname=$dbname;host=$host", $_SESSION['user'], $_SESSION['pass'], array(PDO::ATTR_PERSISTENT => true));
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $query_app = self::$db->prepare("SET application_name = 'PowERPlay CRM'");
            $query_app->execute();
            
            $query_current_bp = self::$db->prepare("SELECT organization.org_id, bp_employee.bp_id
                                                FROM   	organization, bp_employee
                                                WHERE  	organization.org_id = bp_employee.org_id 
                                                        AND username = :user");
            $query_delete_session = self::$db->prepare("DELETE FROM session_user_data WHERE procpid = pg_backend_pid()");
            $query_insert_session = self::$db->prepare("INSERT INTO session_user_data 
                                                (procpid, user_id, org_id, dim1, entered_by, entered_on, last_updated_by, last_updated_on)
                                                SELECT pg_backend_pid(), :current_bp_id, :il_org_id, 0 , :current_bp_id, NOW(), :current_bp_id, NOW()");

            //EXECUTING QUERIES			
            $query_current_bp->execute(array(':user' => $_SESSION['user']));
            $data_current_bp = $query_current_bp->fetch(PDO::FETCH_ASSOC);

            $current_bp_id = $data_current_bp['bp_id'];
            $current_org_id = $data_current_bp['org_id'];
            $_SESSION['current_bp_id'] = $current_bp_id;
            $_SESSION['org_id'] = $current_org_id;

            $query_delete_session->execute();
            $query_insert_session->execute(array(     
                    ':current_bp_id' => $current_bp_id,
                    ':il_org_id' => $current_org_id,
                    ':current_bp_id' => $current_bp_id,
                    ':current_bp_id' => $current_bp_id
            ));
            
            $query_session =  self::$db->prepare("SELECT procpid, user_id FROM session_user_data WHERE procpid = (SELECT pg_backend_pid())");
            $query_session->execute();
            $result = $query_session->fetchAll();
            $data = $result[0];
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['procpid'] = $data['procpid'];
        }
        catch (PDOException $e) {
            self::$db = null;
            log_error('DB CONNECT FAILED. '.$e->getMessage());
            $_SESSION['flash_type'] = "error";
            $_SESSION['flash_msg'] = "Unable to connect to database";
            header('location:index.php?page=login');
        }
    }
    
    public static function getConnection() {
        if (!self::$db) {
            new dbConn();
        }
        return self::$db;
    }
    
    public static function closeConnection() {
        self::$db = null;
    }
}
?>
