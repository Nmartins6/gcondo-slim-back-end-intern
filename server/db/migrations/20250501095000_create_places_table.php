<?php

use Phinx\Migration\AbstractMigration;

class CreatePlacesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('places');
        $table->addColumn('name', 'string', ['null' => false])
            ->addColumn('max_people', 'integer', ['null' => false])
            ->addColumn('square_meters', 'float', ['null' => true])
            ->addTimestamps()
            ->create();
    }
}
