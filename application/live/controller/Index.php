<?php
# @Date:   2018/03/22
# @Email:  runstp@163.com
# @Project: 阿正
# @Filename: Live.php
# @Last modified time: 2018/03/28
# @License: MIT

namespace app\live\controller;

use app\live\controller\Base;
use app\live\model\VideoClass as VideoClassModel;
/**
 *
 */
class Index extends Base
{
    public function index()
    {
        $class_model = new VideoClassModel();
        $this->assign('class_list', $class_model->getClassList());

        return $this->fetch();
    }

    public function room()
    {
        return $this->fetch();
    }

    public function test()
    {
        return $this->fetch();
    }
}
