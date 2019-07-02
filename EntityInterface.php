<?php

/**
 *
 */
interface EntityInterface
{
    /**
     * @param $clauseWhere , exemple : "content like '%le m%'"
     * @return array
     */
    public static function find($clauseWhere): array;

    /**
     * @return void
     */
    public function save();

    /**
     * @param $id
     * @return void
     */
    public function load($id);

}
