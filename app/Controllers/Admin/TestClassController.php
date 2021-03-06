<?php

namespace App\Controllers\Admin;

use App\Models\Svc\ErrorSvc;
use App\Models\Svc\UtlsSvc;
use App\Models\Svc\TestClassSvc;


class TestClassController extends BaseController
{
    const PER_PAGE_NUM = 15;// 默认分页数

    static $NOT_LOGIN_ACTION  = array();// 排除登录验证

    public function __construct()
    {
        $isLogin  = true;
        if (in_array(strtolower($this->getActionName()), self::$NOT_LOGIN_ACTION)) {
            $isLogin = false;
        }
        parent::__construct($isLogin);
    }

    public function indexAction()
    {
        $id = $this->getRequest('id','');
        if ($id > 0) {
            $TestClass = TestClassSvc::getById($id);
            if (is_null($TestClass)) {
                UtlsSvc::showMsg('没有这个ID', '/TestClass/list');
            }
            $this->assign($TestClass->toAry());
        }
        $this->assign('curr_menu', 'TestClass');
        $this->assign('curr_submenu', 'TestClass_add');
        return render('index');
    }


    public function addAction()
    {
        $param = array();
        $id    = $this->getRequest('id','');
        $param['testdatetime'] = $this->getRequest('testdatetime','');
        $param['testdata'] = $this->getRequest('testdata','');
        $param['testtime'] = $this->getRequest('testtime','');
        $param['testint'] = $this->getRequest('testint','');
        $param['testtinyint'] = $this->getRequest('testtinyint','');
        $param['testvarchar'] = $this->getRequest('testvarchar','');
        $param['testint_table'] = $this->getRequest('testint_table','');

        // 参数校验，有时这是必须的
        // if (empty($param['name'])) {
        //     return ErrorSvc::format(ErrorSvc::ERR_PARAM_EMPTY, null, '姓名不能为空');
        // }

        if ($id != '') {
            $param['utime'] = date('Y-m-d H:i:s');
            $obj = TestClassSvc::updateById($id, $param);
            return ErrorSvc::format(ErrorSvc::ERR_OK, null, '保存成功');
        } else {
            $obj = TestClassSvc::add($param);
            return ErrorSvc::format(ErrorSvc::ERR_OK, null, '新增成功');
        }
    }

    public function deleteAction()
    {
        return ErrorSvc::format(
            ErrorSvc::ERR_OK,
            null,
            '请考虑清楚数据是否真的需要删除，是否可以使用状态标识来进行软删除'
        );
    }

    public function listAction()
    {
        $request = array();
        $request['id'] = $this->getRequest('id','');
        $request['startdate'] = $this->getRequest('startdate','');
        $request['enddate'] = $this->getRequest('enddate','');
        $request['utime'] = $this->getRequest('utime','');
        $request['testdatetime'] = $this->getRequest('testdatetime','');
        $request['testdata'] = $this->getRequest('testdata','');
        $request['testtime'] = $this->getRequest('testtime','');
        $request['testint'] = $this->getRequest('testint','');
        $request['testtinyint'] = $this->getRequest('testtinyint','');
        $request['testvarchar'] = $this->getRequest('testvarchar','');
        $request['testint_table'] = $this->getRequest('testint_table','');
        $orderby  = $this->getRequest('orderby');
        // 必须校验 orderby 此处没有做预处理

        $list = TestClassSvc::lists($request, array(
            'per_page'=>self::PER_PAGE_NUM,
            'page_param'=>'p',
            'curr_page'=>$this->getRequest('p',1),
            'file_name'=>'/TestClass/list/',
            'orderby'=>$orderby
        ));

        $this->assign($request);
        $this->assign('orderby', $orderby);
        $this->assign('list', $list);
        $this->assign('curr_menu', 'TestClass');
        $this->assign('curr_submenu', 'TestClass_list');
        return render('list');
    }

    public function exportAction()
    {
        $request = array();
        $request['id'] = $this->getRequest('id','');
        $request['startdate'] = $this->getRequest('startdate','');
        $request['enddate'] = $this->getRequest('enddate','');
        $request['utime'] = $this->getRequest('utime','');
        $request['testdatetime'] = $this->getRequest('testdatetime','');
        $request['testdata'] = $this->getRequest('testdata','');
        $request['testtime'] = $this->getRequest('testtime','');
        $request['testint'] = $this->getRequest('testint','');
        $request['testtinyint'] = $this->getRequest('testtinyint','');
        $request['testvarchar'] = $this->getRequest('testvarchar','');
        $request['testint_table'] = $this->getRequest('testint_table','');
        $orderby  = $this->getRequest('orderby');
        // 必须校验 orderby 此处没有做预处理

        $list = TestClassSvc::lists($request, array(
            'per_page'=>self::PER_PAGE_NUM,
            'page_param'=>'p',
            'curr_page'=>$this->getRequest('p',1),
            'file_name'=>'/TestClass/list/',
            'orderby'=>$orderby
        ), true);

        // 表格导出
        $table = '<table border="1"><tr>
        <th>ID</th><th>创建时间</th><th>修改时间</th><th>datetime</th><th>data</th><th>time</th><th>int</th><th>tinyint</th><th>varchar</th><th>int_table</th>
        </tr>';
        foreach ($list as $k => $v) {
            $table .= '<tr>';
            $table .= '<th>'.$v['id'].'</th><th>'.$v['ctime'].'</th><th>'.$v['utime'].'</th><th>'.$v['testdatetime'].'</th><th>'.$v['testdata'].'</th><th>'.$v['testtime'].'</th><th>'.$v['testint'].'</th><th>'.$v['testtinyint'].'</th><th>'.$v['testvarchar'].'</th><th>'.$v['testint_table'].'</th>';
            $table .= '</tr>';
        }
        $table .= '</table>';
        echo $table;
        // CSV导出
        // $str = "ID,创建时间,修改时间,datetime,data,time,int,tinyint,varchar,int_table\n";
        // foreach ($list as $k => $v) {
            // $id = $v['id'];
            // $ctime = $v['ctime'];
            // $utime = $v['utime'];
            // $testdatetime = $v['testdatetime'];
            // $testdata = $v['testdata'];
            // $testtime = $v['testtime'];
            // $testint = $v['testint'];
            // $testtinyint = $v['testtinyint'];
            // $testvarchar = $v['testvarchar'];
            // $testint_table = $v['testint_table'];
            // $str .= $id.','.$ctime.','.$utime.','.$testdatetime.','.$testdata.','.$testtime.','.$testint.','.$testtinyint.','.$testvarchar.','.$testint_table."\n";
        // }
        // header("Content-type:text/csv");
        // header("Content-Disposition:attachment;filename=".date('Ymd').'.csv');
        // header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        // header('Expires:0');
        // header('Pragma:public');
        // echo $str;
        exit;
    }

}
