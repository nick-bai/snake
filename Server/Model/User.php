<?php
namespace App\Model;

/**
 *
 */
class User extends Model
{
    protected $table_name = 'es_user';

    public function getTest()
    {
        return $this->dbConnector()->get($this->table_name, null, 'user_name');
    }
}
