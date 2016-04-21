<?php

namespace App\Controllers\Admin;

use Elephant\Foundation\Controller;
use App\Models\Svc\UtlsSvc;
use App\Models\Svc\AdmUserSvc;
use App\Models\Svc\AdmAuthSvc;

class BaseController extends Controller
{
    public function __construct($require_login = true)
    {
        if ($require_login) {
            $adminUser      = loader('session')->get('adminUser');
            $adminUserObj   = AdmUserSvc::getByEname($adminUser);
            if (!$adminUserObj) {
                UtlsSvc::goToAct("Index", "login");
            }
            $controllerName = Controller::getControllerName();
            $actionName     = Controller::getActionName();
            $auth = AdmAuthSvc::verify($controllerName, $actionName, $adminUserObj);
            if ($auth == "fail") {
                UtlsSvc::showMsg('您无此权限', '/Index/index/');
            } else {
                $this->assign('adminUser', $adminUser);
                $this->assign('adminUserObj', $adminUserObj);
                return true;
            }
        }
    }
}