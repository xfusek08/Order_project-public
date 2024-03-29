<?php
class LogType
{
    const Announcement = 0;
    const Error = 1;
    const Event = 2;
}

class Logging
{
    public static $FullLogFileName;
    public static $LogFileName;
    
    public static function Init() {
        if (!file_exists(LOG_FOLDER)) {
            mkdir(LOG_FOLDER);
        }
        
        $File = null;
        self::$LogFileName = self::GetActFileName();
        self::$FullLogFileName = LOG_FOLDER . "/" . self::GetActFileName();
        
        if (file_exists(self::$FullLogFileName) && self::IsNewFile()) {
            $File = fopen(self::$FullLogFileName, "a");
            self::WriteLogLine($File, "File ended.");
            fclose($File);
        }
        
        try {
            if (!file_exists(self::$FullLogFileName)) {
                $File = fopen(self::$FullLogFileName, "a");
                self::WriteLogLine($File, "File created: " . self::$FullLogFileName);
                fclose($File);
            }
        } catch (Exception $e) {
            die("Error during log initialization.");
        }
    }
    
    public static function WriteLog($LogType, $Text) {
        self::Init();
        try {
            $Type = "";
            switch ($LogType) {
                case LogType::Error:
                    $Type = "Error";
                    break;
                case LogType::Announcement:
                    $Type = "Announcement";
                    break;
                case LogType::Event:
                    $Type = "Event";
                    break;
            }
            $File = null;
            $File = fopen(self::$FullLogFileName, "a");
            self::WriteLogLine($File, $Type . ": " . $Text);
            fclose($File);
        } catch (Exception $e) {
            die("Error during log.");
        }
    }
    
    private static function GetActFileName() {
        return "RPLog_" . date("Ymd") . ".log";
    }
    
    private static function IsNewFile() {
        return (self::$LogFileName != self::GetActFileName());
    }
    
    private static function WriteLogLine($File, $Text) {
        fwrite($File, date('[' . DATE_TIME_FORMAT . ']') . " " . $Text . PHP_EOL);
    }
}

Logging::Init();
