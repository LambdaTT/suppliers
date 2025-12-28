<?php

namespace Suppliers\Services;

use SplitPHP\Service;
use SplitPHP\Exceptions\BadRequest;

class Supplier extends Service
{
  const TABLE = "SPL_SUPPLIER";

  public function list($params = [])
  {
    return $this->getDao(self::TABLE)
      ->bindParams($params)
      ->find('supplier/read');
  }

  public function get($params = [])
  {
    return $this->getDao(self::TABLE)
      ->bindParams($params)
      ->first('supplier/read');
  }

  public function create($data)
  {
    // Removes forbidden fields from $data
    $data = $this->getService('utils/misc')->dataBlackList($data, [
      'id_spl_supplier',
      'ds_key',
      'id_iam_user_created',
      'id_iam_user_updated',
      'dt_created',
      'dt_updated'
    ]);

    // Set refs
    $loggedUser = null;
    if ($this->getService('modcontrol/control')->moduleExists('iam'))
      $loggedUser = $this->getService('iam/session')->getLoggedUser();

    // Validation
    if (empty($data['do_state']) || !$this->getService('utils/misc')->validateUF($data['do_state']))
      throw new BadRequest("UF inválido.");

    if (!in_array($data['do_person_type'], ['F','J'])) 
      throw new BadRequest("Tipo de pessoa inválida.");

    // Set default values
    $data['ds_key'] = 'spl-' . uniqid();
    $data['id_iam_user_created'] = empty($loggedUser) ? null : $loggedUser->id_iam_user;

    return $this->getDao(self::TABLE)->insert($data);
  }
  
  public function upd($params, $data)
  {
    // Removes forbidden fields from $data
    $data = $this->getService('utils/misc')->dataBlackList($data, [
      'id_spl_supplier',
      'ds_key',
      'id_iam_user_created',
      'id_iam_user_updated',
      'dt_created',
      'dt_updated'
    ]);

    // Set refs
    $loggedUser = null;
    if ($this->getService('modcontrol/control')->moduleExists('iam'))
      $loggedUser = $this->getService('iam/session')->getLoggedUser();

    // Validation
    if (!empty($data['do_state']) && !$this->getService('utils/misc')->validateUF($data['do_state']))
      throw new BadRequest("UF inválido.");

    if (!empty($data['ds_person_type']) && !in_array($data['ds_person_type'], ['F','J'])) 
      throw new BadRequest("Tipo de pessoa inválida.");

    // Set default values
    $data['id_iam_user_updated'] = empty($loggedUser) ? null : $loggedUser->id_iam_user;
    $data['dt_updated'] = date("Y-m-d H:i:s");

    return $this->getDao(self::TABLE)
      ->bindParams($params)
      ->update($data);
  }
  
  public function remove($params){
    return $this->getDao(self::TABLE)
      ->bindParams($params)
      ->delete();
  }
}