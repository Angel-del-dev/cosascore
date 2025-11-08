<?php

namespace barephrame\src\lib\models;

use barephrame\src\lib\connection\Mysql;

class Model {
    protected static Mysql $connection;
    protected static string $Table = '';
    protected static array $Keys = [];
    protected static array $Columns = [];
    protected static function Connection():void {
        self::$connection = new Mysql("{$_SERVER['DOCUMENT_ROOT']}/../config.ini");
    }


    /**
     * [Get records]
     *
     * @param array $Params = [ [KEY, SIGN, VALUE, TYPECONCAT] ]
     * {{$TYPECONCAT = [ _AND, _OR ]}}
     * 
     * @return array]
     * 
     */
    public static function Get($Params = []):array {
        $query = sprintf(" SELECT %s FROM %s ", implode(', ', static::$Columns), static::$Table);
        if(count($Params) > 0) $query .= " WHERE ";
        foreach($Params as $param) {
            $typeconcat = isset($param[3]) ? $param[3] : '';
            $query .= sprintf(' %s %s :%s %s ', $param[0], $param[1], $param[0], $typeconcat);
        }

       self::Connection();
       
       $sql = self::$connection->newQuery($query);
        foreach($Params as $Param) {
            $Key = $Param[0];
            $sql->params->$Key = $Param[2];
        }
        $Data = $sql->Execute();
        $sql->close();

        return $Data;
    }

    /**
     * [Check if records exist from a specific filter]
     *
     * @param array $Params = [ [KEY, SIGN, VALUE, TYPECONCAT] ]
     * {{$TYPECONCAT = ['AND', 'OR']}}
     * 
     * @return bool
     * 
     */
    public static function Exists($Params = []):bool {
        return count(self::Get($Params)) > 0;
    }

    /**
     * [Create a new record]
     *
     * @param array $data
     * {{ $data = [key => value] }}
     * 
     * @return void
     * 
     */
    public static function Insert(array $data):void {
        self::Connection();
        $Keys = [];
        $Values = [];

        foreach($data as $k => $_) {
            $Keys[] = $k;
            $Values[] = ":{$k}";
        }
        
        $query = sprintf("
            INSERT INTO %s (%s) VALUES (%s)
        ", static::$Table, implode(', ', $Keys), implode(', ', $Values));

        $sql = self::$connection->newQuery($query);
        foreach($data as $k => $value) {
            $sql->params->$k = $value;
        }

        $sql->Execute();
        $sql->close();
    }


    /**
     * [Delete function]
     *
     * @param array($key => $value) $DeleteParams
     * 
     * @return [type]
     * 
     */
    public static function Delete($DeleteParams) {
        self::Connection();

        $query = sprintf(" DELETE FROM %s", static::$Table);
        if(count($DeleteParams) > 0) $query .= " WHERE ";
       
        foreach($DeleteParams as $param) {
            $typeconcat = isset($param[3]) ? $param[3] : '';
            $query .= sprintf(' %s %s :%s %s ', $param[0], $param[1], $param[0], $typeconcat);
        }
        
        self::Connection();
       
       $sql = self::$connection->newQuery($query);
        foreach($DeleteParams as $Param) {
            $Key = $Param[0];
            $sql->params->$Key = $Param[2];
        }
        $_ = $sql->Execute();
        $sql->close();
        
    }

    public static function Update($values, $FilterParams = []) {
        self::Connection();

        $query = sprintf(" UPDATE %s SET", static::$Table);

        $Sets = [];
        foreach($values as $column => $_) {
            $Sets[] = sprintf(' %s = :%s ', $column, $column);
        }
        $query .= implode(',', $Sets);

        if(count($FilterParams) > 0) $query .= " WHERE ";
       
        foreach($FilterParams as $param) {
            $typeconcat = isset($param[3]) ? $param[3] : '';
            $query .= sprintf(' %s %s :%s %s ', $param[0], $param[1], $param[0], $typeconcat);
        }
        
        self::Connection();
       
        $sql = self::$connection->newQuery($query);
        foreach($values as $column => $new_value) {
            $sql->params->$column = $new_value;
        }

        foreach($FilterParams as $Param) {
            $Key = $Param[0];
            $sql->params->$Key = $Param[2];
        }
        $_ = $sql->Execute();
        $sql->close();
    }
}