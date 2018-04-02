<?php
# @Date:   2018/03/28
# @Email:  runstp@163.com
# @Project: 阿正
# @Filename: Live.php
# @Last modified time: 2018/03/28
# @License: MIT
namespace app\live\controller;

use app\live\controller\Base;
use app\live\model\Video as VideoModel;
/**
 *
 */
class Live extends Base
{
    public function getLiveList($offset = 1, $limit = 20, $video_class_id = false, $all_list = false)
    {
        $video_model = new VideoModel();
        $video_list = $video_model->getVideoList($offset, $limit, $video_class_id, $all_list);

        return json(msg(200, $video_list, 'ok'));
    }
}
