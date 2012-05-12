<?php

class Wiki {


	public $basePath = '';

	public function setBasePath($path) {
		$this->basePath = $path;
	}

	public function getTree($path = null) {
		if (!$path) {
			$path = $this->basePath;
		}

		$output = array();

		if (is_dir($path) && file_exists($path . '/order.json')) {
			$order = json_decode(file_get_contents($path . '/order.json'), true);
			$dirContent = array();
			foreach ($order AS $orderItem) {
				$dirContent[] = $path . '/' . $orderItem;
			}
		} else {
			$dirContent = glob($path . '/*');
		}
		
		foreach ($dirContent AS $item) {
			if (strstr($item, '.json') || strstr($item, 'index.md')) {
				continue;
			}

			$output[$item] = array(
				'title' => $this->pathToTitle($item),
				'path' => $item,
				'route' => $this->pathToRoute($item),
			);
			if (is_dir($item)) {
				$output[$item]['children'] = $this->getTree($item);
			}
		}

		return $output;
	}

	public function pathToTitle($path) {
		$pathE = explode('/', $path);
		$title = str_replace('.md', '', str_replace('-', ' ', ucfirst(array_pop($pathE))));

		if (is_dir($path)) {
			$path .= '/index.md';
		}
		if (file_exists($path)) {
			$content = file_get_contents($path);
			$contentE = explode("\n", $content);
			if (isset($contentE['0']) && strlen($contentE['0']) > 0 && strstr($contentE['0'], '# ')) {
				$title = str_replace('# ', '', $contentE['0']);
			}
		}

		return $title;
	}

	public function pathToRoute($path) {
		$route = str_replace($this->basePath, '', $path);
		$route = str_replace('/index.md', '', $route);
		$route = str_replace('.md', '', $route);
		return $route;
	}

	public function routeToPath($route) {
		if (file_exists($this->basePath . $route . '.md')) {
			return $this->basePath . $route . '.md';
		} elseif (file_exists($this->basePath . $route . '/index.md')) {
			return $this->basePath . $route . '/index.md';
		} elseif (is_dir($this->basePath . $route)) {
			return $this->basePath . $route;
		}

		return false;
	}

	public function getPage($path) {
		if (!file_exists($path)) {
			return false;
		}

		$page = array();
		$page['title'] = $this->pathToTitle($path);
		$page['path'] = $path;
		$page['route'] = $this->pathToRoute($path);
		
		$content = file_get_contents($path);
		$contentE = explode("\n", $content);
		if (strstr($contentE['0'], '# ')) {
			unset($contentE['0']);
			$content = trim(implode("\n", $contentE));
		}
		$page['content'] = $content;

		return $page;
	}

	public function getBreadcrumbs($route, $showHome = true, $showWiki = true) {
		$breadcrumbs = array();
		if ($showHome) {
			$breadcrumbs['Home'] = 'http://croogo.org';
		}
		if ($showWiki) {
			$breadcrumbs['Wiki'] = 'http://wiki.croogo.org';
		}

		$currentRoute = '';
		$routeE = explode('/', $route);
		foreach ($routeE AS $r) {
			if (strlen($r) == 0) {
				continue;
			}

			$currentRoute .= '/' . $r;
			$currentPath = $this->routeToPath($currentRoute);
			$currentTitle = $this->pathToTitle($currentPath);
			$breadcrumbs[$currentTitle] = $currentRoute;
		}

		return $breadcrumbs;
	}

	public function showMenu($tree) {
		$output = '<ul>';
		foreach ($tree AS $path => $item) {
			$class = '';
			if (Core::url($item['route']) == Core::url(Core::getCurrentRoute())) {
				$class = 'selected';
			}

			$output .= '<li>';
			$output .= '<a class="' . $class . '" href="' . Core::url($item['route']) . '">' . $item['title'] . '</a>';
			if (isset($item['children']) && count($item['children']) > 0) {
				$output .= $this->showMenu($item['children']);
			}
			$output .= '</li>';
		}
		$output .= '</ul>';
		return $output;
	}

	public function showBreadcrumbs($breadcrumbs) {
		$links = array();
		foreach ($breadcrumbs AS $title => $route) {
			$class = '';
			if (Core::url($route) == Core::url(Core::getCurrentRoute())) {
				$class = 'selected';
			}

			if (strlen($title) > 0) {
				$links[] = '<a class="' . $class . '" href="' . Core::url($route) . '">' . $title . '</a>';
			}
		}
		return implode(' &rarr; ', $links);
	}

	public function showPageTitle($breadcrumbs) {
		$titles = array('Wiki');
		$i = 0;
		$error404 = false;
		foreach ($breadcrumbs AS $title => $route) {
			if ($i > 1) {
				if (strlen($title) == 0) {
					$error404 = true;
				} else {
					$titles[] = $title;
				}
			}

			$i++;
		}
		return implode(' &raquo; ', $titles);
	}

	public function getChildren($route, $tree) {
		$output = array();
		foreach ($tree AS $path => $item) {
			if (count($output) == 0 && $route == $item['route'] && !empty($item['children'])) {
				$output = $item['children'];
			}

			if (count($output) == 0 && !empty($item['children'])) {
				$output = $this->getChildren($route, $item['children']);
			}
		}
		return $output;
	}

	public function showChildren($children) {
		$output = '<ul>';
		foreach ($children AS $path => $child) {
			$output .= '<li>';
			$output .= '<a href="' . Core::url($child['route']) . '">' . $child['title'] . '</a>';
			$output .= '</li>';
		}
		$output .= '</ul>';
		return $output;
	}

}

?>