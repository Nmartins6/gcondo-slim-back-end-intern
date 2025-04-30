<?php

use Phinx\Migration\AbstractMigration;

class MakeUrlNullableOnCondominiums extends AbstractMigration
{
    public function change()
    {
        // Change the 'url' column to accept null values
        $this->table('condominiums')
        ->changeColumn('url', 'string', ['null' => true])
        ->update();
    }
}