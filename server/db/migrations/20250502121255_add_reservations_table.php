<?php

use Phinx\Migration\AbstractMigration;

class AddReservationsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('reservations');
        $table->addColumn('name', 'string', ['null' => false])
              ->addColumn('unit_id', 'integer', ['null' => false, 'signed' => false])
              ->addColumn('place_id', 'integer', ['null' => true, 'signed' => false])
              ->addColumn('people_amount', 'integer', ['null' => false])
              ->addColumn('date', 'date', ['null' => false])
              ->addTimestamps()
              ->addForeignKey('unit_id', 'units', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('place_id', 'places', 'id', ['delete' => 'SET NULL'])
              ->create();
    }
}