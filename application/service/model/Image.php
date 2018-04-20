<?php
# @Date:   2018/03/27
# @Email:  runstp@163.com
# @Project: 阿正
# @Filename: Image.php
# @Last modified time: 2018/03/27
# @License: MIT
namespace app\service\model;

use think\Model;

/**
 *
 */
class Image extends Model
{
    public $error_msg;

    public function saveImage($save_path, $md5)
    {
        if (null !== $this::get(['md5' => $md5])) {
            return $md5;
        }

        try {
            $this->save(['path' => $save_path, 'md5' => $md5]);
            return $save_path;
        } catch (\PDOException $e) {
            $this->$error_msg = $e->getMessage();
            return false;
        }
    }
}
