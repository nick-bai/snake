<?php

namespace Qsnh\Think\Log\Controllers;

use think\Controller;
use Qsnh\Think\Log\Models\Log;
use think\paginator\driver\Bootstrap AS BootstrapPaginator;

class LogViewerController extends Controller
{

	public function index()
	{
		$page = request()->param('page', 1);
		$pageSize = request()->param('page_size', 10);
		$file = request()->param('file');

		$log = new Log;
		$files = $log->files();
		$default = $file ?: $files[0]['real'] ?? '';
		$data = $default ? $log->paginate($default, $page, $pageSize) : [];
		// 分页信息
		$paginator = BootstrapPaginator::make($data['data'], $data['meta']['page_size'], $data['meta']['current_page'], $data['meta']['total'], false, [
			'path' => '',
			'query' => request()->param(),
		])->render();

		return view(dirname(__FILE__) . '/../Views/index.html', compact('files', 'default', 'data', 'paginator'));
	}

}
