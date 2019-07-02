<?php

require_once "EntityInterface.php";
require_once "Mysql.php";

abstract class Entity implements EntityInterface
{

    protected static $tableName = NULL;

    public static function find($clauseWhere): array
    {
        $sqlQuery = "SELECT * FROM " . static::getTableName() . " WHERE " . $clauseWhere;
        $result = Mysql::getInstance()->query($sqlQuery)->fetchAll(PDO::FETCH_ASSOC);

        return [];
    }

    /**
     * @return null
     */
    public static function getTableName()
    {
        $reflection = new ReflectionClass(get_called_class());
        return NULL !== static::$tableName ? static::$tableName : strtolower($reflection->getName());
    }

    public function save()
    {

        $reflection = new ReflectionClass($this);
        $props = array();
        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            if ($propertyName !== "id") {
                $props[] = '`' . $propertyName . '` = "' . $this->{$propertyName} . '"';
            }
        }

        $sqlQuery = "INSERT INTO " . static::getTableName() . " SET " . implode(' , ', $props);
        Mysql::getInstance()->query($sqlQuery);
    }

    /**
     * @param $id
     * @throws Exception
     */
    public function load($id)
    {
        $sqlQuery = "SELECT * FROM " . static::getTableName() . " WHERE id = " . $id;
        $result = Mysql::getInstance()->query($sqlQuery)->fetchAll(PDO::FETCH_ASSOC);
        $loaded = array_shift($result);

        if (NULL === $loaded) {
            throw new Exception("Load failed");
        }

        $reflection = new ReflectionClass($this);
        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            if (isset($loaded[$propertyName])) {
                $this->{$propertyName} = $loaded[$propertyName];
            }
        }
    }
}
