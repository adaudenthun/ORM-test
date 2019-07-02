<?php

require_once "Mysql.php";

/**
 * Class Migrate
 */
class Migrate
{

    /**
     *
     */
    CONST DIR_BASE = "./config";
    /**
     *
     */
    CONST DIR_VERSION = "./migration";

    /**
     * @var
     */
    private $files;
    /**
     * @var void
     */
    private $currentFileName;

    /**
     * Migrate constructor.
     */
    public function __construct()
    {
        $this->initFileList();
        $this->validateConf();
    }

    /**
     *
     */
    private function initFileList()
    {
        foreach (scandir(self::DIR_BASE) as $item) {
            if (!in_array($item, array(".", ".."))) {
                $this->files[] = $item;
            }
        }
    }

    /**
     * @param $conf
     * @throws Exception
     */
    private function validateConf()
    {
        foreach ($this->files as $file) {

            $conf = json_decode(file_get_contents(self::DIR_BASE . '/' . $file), true);
            if (!isset($conf['fields'])) {
                throw new Exception('File : ' . $this->currentFileName . ' == Must have node "fileds"');
            }
            foreach ($conf['fields'] as $fieldName => $fieldConf) {

                if (!isset($fieldConf['type'])) {
                    throw new Exception('File : ' . $this->currentFileName . ' == Must have "type" for type ' . $fieldName);
                }

            }

        }
    }

    /**
     * @throws Exception
     */
    function execute()
    {
        foreach ($this->files as $file) {

            $string = file_get_contents(self::DIR_BASE . '/' . $file);
            $this->currentFileName = $file;

            if(file_exists(self::DIR_VERSION . '/' . $file)){
                $this->runUpdate(json_decode($string, true));
                continue;
            }
            $this->runCreate(json_decode($string, true));
        }
    }

    private function runUpdate($conf){

        $string = file_get_contents(self::DIR_BASE . '/' . $this->currentFileName);
        $confBefore = json_decode($string, true);
        $this->doUpdateDiff($conf,$confBefore);

    }

    /**
     * @param $conf
     * @throws Exception
     */
    private function runCreate($conf)
    {

        $queryField = [];
        foreach ($conf['fields'] as $fieldName => $fieldConf) {
            $queryField[] = $fieldName . " " . $fieldConf['type'] . (isset($fieldConf['property']) ? " " . $fieldConf['property'] : "");

        }
        $query = "CREATE TABLE " . $this->getTableName($conf) . " ( " . implode(',', $queryField) . " ) ";
        $result = Mysql::getInstance()->query($query);

        if (!$result) {
            throw new Exception('File : ' . $this->currentFileName . ' == Error during create table "' . $this->getTableName($conf) . '"');
        }
    }

    /**
     * @param $conf
     * @return mixed|string|string[]|null
     */
    private function getTableName($conf)
    {
        return isset($conf['tableName']) ? $conf['tableName'] : preg_replace('/\\.[^.\\s]{3,4}$/', '', $this->currentFileName);
    }

    function doUpdateDiff($newConf, $oldConf){
        $array_diff = [];

        if(count($newConf['fields']) < count($oldConf['fields'])){
            // TODO : remove field(s)
        }

        if(count($newConf['fields']) > count($oldConf['fields'])){
            // TODO : add field(s)
        }

        if(count($newConf['fields']) == count($oldConf['fields'])){
            // TODO : Check if have same name else remove and add new field()
        }


        var_dump(count($newConf));
        die;

        return $array_diff;
    }


}


try {

    $migrate = new Migrate();
    $migrate->execute();
} catch (Exception $e) {
    print_r($e->getMessage());
}
