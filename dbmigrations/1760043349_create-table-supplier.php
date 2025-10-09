<?php

namespace Suppliers\Migrations;

use SplitPHP\DbManager\Migration;
use SplitPHP\Database\DbVocab;

class CreateTableSupplier extends Migration
{
  public function apply()
  {
    $this->Table('SPL_SUPPLIER')
      ->id('id_spl_supplier')
      ->string('ds_key', 17)
      ->datetime('dt_created')->setDefaultValue(DbVocab::SQL_CURTIMESTAMP())
      ->datetime('dt_updated')->nullable()->setDefaultValue(null)
      ->int('id_iam_user_created')->nullable()->setDefaultValue(null)
      ->int('id_iam_user_updated')->nullable()->setDefaultValue(null)
      ->string('ds_name', 100)
      ->string('do_person_type', 1) // F=Física J=Jurídica
      ->string('ds_taxid', 20)
      ->string('ds_phone1', 15)->nullable()->setDefaultValue(null)
      ->string('ds_phone2', 15)->nullable()->setDefaultValue(null)
      ->string('ds_whatsapp_no', 15)->nullable()->setDefaultValue(null)
      ->string('ds_email', 255)->nullable()->setDefaultValue(null)
      ->text('tx_details')->nullable()->setDefaultValue(null)
      ->Index('KEY', DbVocab::IDX_UNIQUE)->onColumn('ds_key');
  }
}
