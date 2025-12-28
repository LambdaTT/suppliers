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
      ->find('suppliers/read');
  }

  public function get($params = [])
  {
    return $this->getDao(self::TABLE)
      ->bindParams($params)
      ->first('suppliers/read');
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
    if (!in_array($data['do_person_type'], ['F', 'J']))
      throw new BadRequest("Tipo de pessoa inválida.");

    $address = $this->getService('addresses/address')->create($data);

    // Set default values
    $data['id_adr_address'] = $address->id_adr_address;
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
    $spl = $this->get($params);
    $loggedUser = null;
    if ($this->getService('modcontrol/control')->moduleExists('iam'))
      $loggedUser = $this->getService('iam/session')->getLoggedUser();

    // Validation
    if (!empty($data['ds_person_type']) && !in_array($data['ds_person_type'], ['F', 'J']))
      throw new BadRequest("Tipo de pessoa inválida.");

    if (empty($spl->id_adr_address))
      $data['id_adr_address'] = $this->getService('addresses/address')->create($data)->id_adr_address;
    else
      $this->getService('addresses/address')->upd(['id_adr_address' => $spl->id_adr_address], $data);

    // Set default values
    $data['id_iam_user_updated'] = empty($loggedUser) ? null : $loggedUser->id_iam_user;
    $data['dt_updated'] = date("Y-m-d H:i:s");

    return $this->getDao(self::TABLE)
      ->bindParams($params)
      ->update($data);
  }

  public function remove($params)
  {
    foreach ($this->list($params) as $spl)
      if (!empty($spl->id_adr_address))
        $this->getService('addresses/address')->remove(['id_adr_address' => $spl->id_adr_address]);

    return $this->getDao(self::TABLE)
      ->bindParams($params)
      ->delete();
  }
}
