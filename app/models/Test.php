<?php
namespace App\Models;

use Phalcon\Mvc\Model;

class Test extends Model
{
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('db_crm');
        $this->setSchema("crm_user");
        $this->setSource("t_users");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 't_users';
    }

}
