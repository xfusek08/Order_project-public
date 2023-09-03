<?php
class MyDatabase
{
    const DUPLICATE_CODE = 1062;
    
    public static $UserName = DATABASE_USER;
    public static $Password = DATABASE_PASSWORD;
    
    public static $TransactionOpen;
    
    public static $PDO;
    
    public static function Connect() {
        self::$TransactionOpen = false;
        
        if (!isset($PDO)) {
            $settings = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                // s timhle nefunguji datetime typy pri query
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_AUTOCOMMIT => 0
            );
            
            try {
                self::$PDO = new PDO(
                    DATABASE_DNS . ";charset=UTF8",
                    self::$UserName,
                    self::$Password,
                    $settings
                );
            } catch (PDOException $e) {
                Logging::WriteLog(LogType::Error, "Database connection error; " . $e->getMessage());
                echo $e->getMessage() . '</br>';
                die("Database connection failed"); // asi rovnou neukoncovat celou stranku ale t5eba se vratit ...
            }
        }
    }
    
    public static function RunQuery(&$fields, $SQL, $ExternTransaction, $params = false) {
        // Logging::WriteLog(LogType::Announcement, "MyDatabase->RunQuery; SQL:" . $SQL);
        // Logging::WriteLog(LogType::Announcement, "MyDatabase->RunQuery; params:". PHP_EOL . print_r($params, true));
        
        $fields = null;
        try {
            if (!$ExternTransaction) {
                self::$PDO->beginTransaction();
            }
            
            $query = self::$PDO->prepare($SQL);
            
            if (!$params) {
                $query->execute();
            } elseif (!is_array($params)) {
                $query->execute(array($params));
            } else {
                $query->execute($params);
            }
            
            $fields = $query->fetchAll();
            
            if (!$ExternTransaction) {
                self::$PDO->commit();
            }
        } catch (PDOException $e) {
            Logging::WriteLog(LogType::Error, "MyDatabase->RunQuery; " . $e->getMessage());
            Logging::WriteLog(LogType::Error, "MyDatabase->RunQuery; SQL: " . $SQL);
            if (!$ExternTransaction) {
                Logging::WriteLog(LogType::Announcement, "RollBack");
                self::$PDO->rollBack();
            }
            $fields = $e->errorInfo[1];
            return false;
        }
        return true;
    }
    
    public static function GetOneValue(&$Val, $SQL, $params = false) {
        $fields = null;
        
        if (!self::RunQuery($fields, $SQL, false, $params)) {
            return false;
        }
        
        if ($fields) {
            $Val = $fields[0][0];
        }
        return true;
    }
}

MyDatabase::Connect();
