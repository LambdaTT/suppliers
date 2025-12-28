<?php

namespace Suppliers\Migrations;

use SplitPHP\DbManager\Migration;
use SplitPHP\Database\DbVocab;

class AddServiceAndProductField extends Migration
{
  public function apply()
  {
    $this->Table('SPL_SUPPLIER')
      ->string('ds_service_product', 255)->nullable()->setDefaultValue(null)
      ->int('id_adr_address')->nullable()->setDefaultValue(null)
      ->Foreign('id_adr_address')->references('id_adr_address')->atTable('ADR_ADDRESS')->onUpdate(DbVocab::FKACTION_CASCADE)->onDelete(DbVocab::FKACTION_SETNULL);
  }
}
