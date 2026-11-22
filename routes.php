<?php

namespace Suppliers;

use SplitPHP\Request;
use SplitPHP\WebService;
use SplitPHP\Exceptions\Unauthorized;
use SplitPHP\ModsManager;

class Routes extends WebService
{
  const SERVICE = 'suppliers/supplier';

  private const SUPPLIER_PERMISSIONS = [
    'read'    => 'suppliers.supplier.read',
    'manage'  => 'suppliers.supplier.manage'
  ];

  public function init()
  {
    /////////////////
    // SUPPLIER ENDPOINTS:
    /////////////////

    $this->addEndpoint('GET', "/v1/supplier/?key?", function (Request $req) {
      $this->auth(self::SUPPLIER_PERMISSIONS);

      $params = [
        $req->getRoute()->params['key']
      ];

      $data = $this->getService(self::SERVICE)->get($params);
      if (empty($data)) return $this->response->withStatus(404);

      return $this->response
        ->withStatus(200)
        ->withData($data);
    });

    $this->addEndpoint('GET', '/v1/supplier', function (Request $req) {
      $this->auth(self::SUPPLIER_PERMISSIONS);

      $params = $req->getBody();

      return $this->response
        ->withStatus(200)
        ->withData($this->getService(self::SERVICE)->list($params));
    });

    $this->addEndpoint('POST', '/v1/supplier', function (Request $req) {
      $this->auth(self::SUPPLIER_PERMISSIONS['manage']);

      $data = $req->getBody();

      return $this->response
        ->withStatus(201)
        ->withData($this->getService(self::SERVICE)->create($data));
    });

    $this->addEndpoint('PUT', "/v1/supplier/?key?", function (Request $req) {
      $this->auth(self::SUPPLIER_PERMISSIONS['manage']);

      $data = $req->getBody();
      $params = [
        'ds_key' => $req->getRoute()->params['key']
      ];

      $result = $this->getService(self::SERVICE)->upd($params, $data);
      if ($result < 1) return $this->response->withStatus(404);

      return $this->response
        ->withStatus(204);
    });

    $this->addEndpoint('DELETE', "/v1/supplier/?key?", function (Request $req) {
      $this->auth(self::SUPPLIER_PERMISSIONS['manage']);

      $params = [
        'ds_key' => $req->getRoute()->params['key']
      ];

      $result = $this->getService(self::SERVICE)->remove($params);
      if ($result < 1) return $this->response->withStatus(404);

      return $this->response
        ->withStatus(204);
    });
  }

  private function auth(array|string $permissions)
  {
    if (!ModsManager::modExists('iam')) return;

    // Auth user login:
    if (!$this->getService('iam/session')->authenticate())
      throw new Unauthorized("Não autorizado.");

    // Validate user permissions:
    $this->getService('iam/permission')->requireAny($permissions);
  }
}
