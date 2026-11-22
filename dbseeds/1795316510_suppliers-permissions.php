<?php

namespace Suppliers\Seeds;

use SplitPHP\DbManager\Seed;
use SplitPHP\ModsManager;

class SuppliersPermissions extends Seed
{
  public function apply()
  {
    if (!ModsManager::modExists('iam')) return;

    $this->SeedTable('IAM_PERMISSION', batchSize: 1)
      ->onField('ds_title')->setFixedValue('Ler Informações de Fornecedores')
      ->onField('ds_tag')->setFixedValue('suppliers.supplier.read')
    ;

    $this->SeedTable('IAM_PERMISSION', batchSize: 1)
      ->onField('ds_title')->setFixedValue('Gerenciar Informações de Fornecedores')
      ->onField('ds_tag')->setFixedValue('suppliers.supplier.manage')
    ;
  }
}
