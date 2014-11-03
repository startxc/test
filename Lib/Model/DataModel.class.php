<?php

/*
 * 备份数据模型
 * author: kewen
 */

class DataModel extends Model {

    var $error = '';
    var $backupDir = '';
    
    var $msgs = array();

    public function __construct() {
        parent::__construct();
        $this->backupDir = APP_PATH . "/Public/Backup/";
    }

    //全部数据表
    public function listTable() {
        $sql = "show table status from " . C("DB_NAME");
        return $this->query($sql);
    }

    //全部数据表名称
    public function listTableByName() {
        $sql = "show table status from " . C("DB_NAME");
        $tmp = $this->query($sql);
        $tables = array();
        foreach ($tmp as $value) {
            $tables[] = $value['Name'];
        }
        return $tmp ? $tables : false;
    }

    //全部字段
    public function listField($tb) {
        $sql = 'describe ' . $tb;
        $result = $this->query($sql);
        $tmp = array();
        foreach ($result as $value) {
            $tmp[] = $value['Field'];
        }
        unset($result);
        return (empty($tmp) ? false : $tmp);
    }

    //查看表格的详细的结构
    public function listFieldInfo($tb) {
        $sql = 'describe ' . $tb;
        return $this->query($sql);
    }

    //导出表格的结构sql
    function showCreateTable($tb) {
        $sql = 'show create table ' . $tb;
        $tmp = $this->query($sql);
        return ($tmp ? preg_replace("/\\n/", "", $tmp[0]['Create Table']) . "\n" : false);
    }

    //导出头部文件sql
    public function headerCreateSql($tb) {
        $header = "DROP TABLE IF EXISTS " . $tb . "\n";
        $createTable = $this->showCreateTable($tb);
        return $header . $createTable;
    }

    //一个表数据sql
    public function everyInsertSql($tb) {
        $sql = 'select * from ' . $tb;
        $data = $this->query($sql);
        $field = $this->listField($tb);
        $insert = '';
        foreach ($data as $v) {
            $sql = '';
            foreach ($field as $f) {
                $sql .= '\'' . mysql_escape_string($v[$f]) . '\',';
            }
            $insert .= 'INSERT INTO ' . $tb . ' VALUES( ' . substr($sql, 0, -1) . ')' . "\n";
        }
        return $insert;
    }

    //完整导出一个表的结构和数据
    public function oneOfTableSql($tb) {
        $sql = '';
        $sql = $this->headerCreateSql($tb) . $this->everyInsertSql($tb);
        return $sql;
    }

    //分卷备份
    public function volumeOfFile($size) {
        $length = empty($size) ? 1024*1000 : $size * 100;
        $size = empty($size) ? 512 : $size;
        $all = $this->listTableByName();
        $sql = '';
        $volume = 1;
        foreach ($all as $tb) {
            $sql .= $this->headerCreateSql($tb);
            $data = $this->query('select * from ' . $tb);
            $field = $this->listField($tb);
            foreach ($data as $v) {
                $tmp = '';
                foreach ($field as $f) {
                    $tmp .= '\'' . $v[$f] . '\',';
                }
                $sql .= 'INSERT INTO ' . $tb . ' VALUES( ' . substr($tmp, 0, -1) . ')' . "\n";
                
                //分卷备份
                
                if (isset($sql{$length})) {
                    $filename = date("Ymd_His", time()) . '_' . $volume .'.sql';
                    $sign = $this->writeToFile($filename, $sql);
                    $this->msgs[] = $sign ? '备份卷' . $volume . '成功' : '备份卷' . $volume . '失败';
                    $sql = '';
                    $volume++;
                }
            }
        }
        if ($sql != '') {
            $filename = date("Ymd_His", time()) . '_' . $volume .'.sql';
            $sign = $this->writeToFile($filename, $sql);
            $this->msgs[] = $sign ? '备份卷' . $volume . '成功' : '备份卷' . $volume . '失败';
            $sql = '';
        }
    }

    //保存备份文件
    public function writeToFile($filename, $sql) {
        if (!is_dir($this->backupDir)) {
            @mkdir($this->backupDir, 0777);
        }
        if (!is_writable($this->backupDir)) {
            $this->error = '备份目录不可写';
            return false;
        }
        return file_put_contents($this->backupDir . $filename, $sql);
    }
    
    //备份文件列表
    public function listFromFile() {
        if(!is_readable($this->backupDir)) {
            $this->error = '备份目录不可读';
            return false;
        }
        $result = glob($this->backupDir.'*.sql');
        $files = array();
        foreach($result as $value) {
           $files[] = str_replace($this->backupDir, "", $value);
        }
        return $files;
    }
    
    //全部保存为一个sql文件
    public function BackupForAllOne() {
        $all = $this->listTableByName();
        $sql = '';
        foreach ($all as $value) {
            $sql .= $this->oneOfTableSql($value);
        }
        $filename = date("Ymd_His", time()) . "_all.sql";
        return $this->writeToFile($filename, $sql);
    }
    
    //优化的分卷备份
    public function backupForAllVolume($size) {
        $length = empty($size) ? 1024*1000 : $size * 1000;
        $tables = $this->listTableByName();
        $sql = '';
        $volume = 1;
        foreach($tables as $tb) {
            $sql .= $this->headerCreateSql($tb);
            $field = $this->listField($tb);
            $query = 'select * from '. $tb;
            $resource = mysql_query($query);
            while($row = mysql_fetch_assoc($resource)) {
                $tmp = '';
                foreach ($field as $f) {
                    $tmp .= '\'' . $row[$f] . '\',';
                }
                $sql .= 'INSERT INTO ' . $tb . ' VALUES( ' . substr($tmp, 0, -1) . ')' . "\n";
                //分卷备份
                if (isset($sql{$length})) {
                    $filename = date("Ymd_His", time()) . '_' . $volume .'.sql';
                    $sign = $this->writeToFile($filename, $sql);
                    $this->msgs[] = $sign ? '备份卷' . $volume . '成功' : '备份卷' . $volume . '失败';
                    $sql = '';
                    $volume++;
                }
            }
        }
        if ($sql != '') {
            $filename = date("Ymd_His", time()) . '_' . $volume .'.sql';
            $sign = $this->writeToFile($filename, $sql);
            $this->msgs[] = $sign ? '备份卷' . $volume . '成功' : '备份卷' . $volume . '失败';
            $sql = '';
        }
    }
    
    //备份到一个文件
    public function backupForSomeOne($tables) {
        $sql = '';
        foreach ($tables as $value) {
            $sql .= $this->oneOfTableSql($value);
        }
        $filename = date("Ymd_His", time()) . "_tables.sql";
        return $this->writeToFile($filename, $sql);
    }
    
    //备份指定的表
    public function backupForSomeVolume($tables, $size) {
        $length = empty($size) ? 1024*1000 : $size * 1000;
        $sql = '';
        $volume = 1;
        foreach($tables as $tb) {
            $sql .= $this->headerCreateSql($tb);
            $field = $this->listField($tb);
            $query = 'select * from '. $tb;
            $resource = mysql_query($query);
            while($row = mysql_fetch_assoc($resource)) {
                $tmp = '';
                foreach ($field as $f) {
                    $tmp .= '\'' . $row[$f] . '\',';
                }
                $sql .= 'INSERT INTO ' . $tb . ' VALUES( ' . substr($tmp, 0, -1) . ')' . "\n";
                //分卷备份
                if (isset($sql{$length})) {
                    $filename = date("Ymd_His", time()) . '_' . $volume .'.sql';
                    $sign = $this->writeToFile($filename, $sql);
                    $this->msgs[] = $sign ? '备份卷' . $volume . '成功' : '备份卷' . $volume . '失败';
                    $sql = '';
                    $volume++;
                }
            }
        }
        if ($sql != '') {
            $filename = date("Ymd_His", time()) . '_' . $volume .'.sql';
            $sign = $this->writeToFile($filename, $sql);
            $this->msgs[] = $sign ? '备份卷' . $volume . '成功' : '备份卷' . $volume . '失败';
            $sql = '';
        }
    }
    
    //还原
    public function import($filename) {
        $filename = $this->backupDir.$filename;
        $files = file($filename);
        foreach ($files as $key => $value) {
            $value = str_replace("\n", "", $value);
            $value = str_replace("\r", "", $value);
            $sign = mysql_query($value);
            if(!$sign) { return false; }
        }
        return true;
    }
}

?>
