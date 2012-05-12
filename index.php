<?php
	require_once __DIR__ . '/inc/bootstrap.php';
	require_once __DIR__ . '/inc/core.php';
	require_once __DIR__ . '/inc/wiki.php';
	require_once __DIR__ . '/inc/markdown_parser.php';

	$core = new Core();
	$wiki = new Wiki();
	$wiki->setBasePath(__DIR__ . '/documentation/en');

	$tree = $wiki->getTree();
	$route = $core->getCurrentRoute();
	$path = $wiki->routeToPath($route);
	$breadcrumbs = $wiki->getBreadcrumbs($route);
	$page = $wiki->getPage($path);
	$children = $wiki->getChildren($route, $tree);
	if (strlen($page['route']) == 0) {
		$children = $tree;
	}

	if (!isset($page['title'])) {
		$error404 = true;
		header('HTTP/1.0 404 Not Found');
		$page['title'] = 'Page not found';
		$page['content'] = 'The requested URL was not found in the server.';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <title><?php echo $wiki->showPageTitle($breadcrumbs); ?> | Croogo</title>
        <meta name="description" content="Croogo - A CakePHP powered Content Management System" /><meta name="generator" content="Croogo - Content Management System" /><meta name="keywords" content="croogo, Croogo" /><meta name="robots" content="index, follow" /><link href="http://feeds.feedburner.com/croogo" type="application/rss+xml" rel="alternate" title="RSS 2.0" /> 
	<?php echo $core->js('jquery.min'); ?>
	<?php echo $core->js('prettify'); ?>
	<?php echo $core->css('reset'); ?>
	<?php echo $core->css('960'); ?>
	<?php echo $core->css('theme'); ?>
	<?php echo $core->css('dessert'); ?>
	<script type="text/javascript">
	    //<![CDATA[
 
	    $(document).ready(function(){
		$('.wiki-menu ul ul').hide();
		$('.wiki-menu ul li a.selected').parents('ul').show();
		$('.wiki-menu a.selected').next().show();
		prettyPrint();
	    });
	    //]]>
	</script>
    </head>
    <body>
        <div id="wrapper"> 
            <div id="header"> 
                <div class="container_16"> 
                    <div id="logo" class="grid_4"> 
                        <a href="http://croogo.org">Croogo</a>
                    </div> 

                    <div id="nav" class="grid_12"> 
                        <div id="menu-3" class="menu"> 
			    <ul>
				    <li><a href="http://croogo.org" id="link-7">Home</a></li>
				    <li>/</li>
				    <li><a href="http://wiki.croogo.org" id="link-18" class="selected">Wiki</a></li>
				    <li>/</li>
				    <li><a href="http://blog.croogo.org" id="link-23">Blog</a></li>
				    <li>/</li>
				    <li><a href="http://wiki.croogo.org/extensions" id="link-22">Extensions</a></li>
				    <li>/</li>
				    <li><a href="http://croogo.org/support" id="link-20">Support</a></li>
				    <li>/</li>
				    <li><a href="http://croogo.org/download" id="link-21">Download</a></li>
			    </ul>
			</div>

			<div class="clear"></div>
                    </div> 

                    <div class="clear"></div> 
                </div> 
            </div> 


            <div id="main"> 
                <div class="container_16"> 
                    <div id="content"> 
                        <div class=""> 
			    <div class="wiki index">
				<div class="breadcrumb">
				    <?php echo $wiki->showBreadcrumbs($breadcrumbs); ?>
				</div>

				<h1><?php echo $page['title']; ?></h1>

				<div class="grid_11">
				    <div class="wiki-content">
					<?php
						$parser = new Markdown_Parser;
						$pageContent = $parser->transform($page['content']);
						$pageContent = str_replace('<pre>', '<pre class="prettyprint">', $pageContent);
						echo $pageContent;
					?>

					&nbsp;
				    </div>

				    <?php if (count($children) > 0) : ?>
				    <div class="wiki-children">
					<h3>Content</h3>
					<?php echo $wiki->showChildren($children); ?>
				    </div>
				    <?php endif; ?>
				</div>

				<div class="grid_5">
				    <div class="wiki-menu">
					<?php echo $wiki->showMenu($tree); ?>
					<!--
					<ul><li><a href="/wiki" class="selected">Home</a></li><li><a href="/wiki/about">About</a><ul><li><a href="/wiki/about/license">License</a></li><li><a href="/wiki/about/contributors">Contributors</a></li><li><a href="/wiki/about/roadmap">Roadmap</a></li></ul></li><li><a href="/wiki/getting-started">Getting started</a><ul><li><a href="/wiki/getting-started/features">Features</a></li><li><a href="/wiki/getting-started/demo">Demo</a></li><li><a href="/wiki/getting-started/download">Download</a></li><li><a href="/wiki/getting-started/requirements">Requirements</a></li><li><a href="/wiki/getting-started/installation">Installation</a></li><li><a href="/wiki/getting-started/troubleshooting">Troubleshooting</a><ul><li><a href="/wiki/getting-started/troubleshooting/increase-php-memory-limit">Increase PHP memory limit</a></li></ul></li><li><a href="/wiki/getting-started/faq">FAQ</a></li><li><a href="/wiki/getting-started/links">Links</a></li></ul></li><li><a href="/wiki/administrators">Administrators</a><ul><li><a href="/wiki/administrators/content">Content</a><ul><li><a href="/wiki/administrators/content/content-types">Content types</a></li><li><a href="/wiki/administrators/content/taxonomy">Taxonomy</a></li><li><a href="/wiki/administrators/content/custom-fields">Custom fields</a></li><li><a href="/wiki/administrators/content/meta-tags">Meta tags</a></li><li><a href="/wiki/administrators/content/multilingual">Multilingual</a></li></ul></li><li><a href="/wiki/administrators/menus">Menus</a></li><li><a href="/wiki/administrators/blocks">Blocks</a></li><li><a href="/wiki/administrators/extensions">Extensions</a><ul><li><a href="/wiki/administrators/extensions/themes">Themes</a></li><li><a href="/wiki/administrators/extensions/plugins">Plugins</a></li><li><a href="/wiki/administrators/extensions/locales">Locales</a></li></ul></li><li><a href="/wiki/administrators/media">Media</a><ul><li><a href="/wiki/administrators/media/attachments">Attachments</a></li><li><a href="/wiki/administrators/media/file-manager">File manager</a></li></ul></li><li><a href="/wiki/administrators/contacts">Contacts</a></li><li><a href="/wiki/administrators/users">Users</a><ul><li><a href="/wiki/administrators/users/roles">Roles</a></li><li><a href="/wiki/administrators/users/permissions">Permissions</a></li></ul></li><li><a href="/wiki/administrators/settings">Settings</a><ul><li><a href="/wiki/administrators/settings/site">Site</a></li><li><a href="/wiki/administrators/settings/meta">Meta</a></li><li><a href="/wiki/administrators/settings/reading">Reading</a></li><li><a href="/wiki/administrators/settings/writing">Writing</a></li><li><a href="/wiki/administrators/settings/comment">Comment</a></li><li><a href="/wiki/administrators/settings/service">Service</a></li><li><a href="/wiki/administrators/settings/languages">Languages</a></li></ul></li></ul></li><li><a href="/wiki/developers">Developers</a><ul><li><a href="/wiki/developers/working-with-git">Working with Git</a></li><li><a href="/wiki/developers/understanding-cakephp">Understanding CakePHP</a><ul><li><a href="/wiki/developers/understanding-cakephp/controllers">Controllers</a></li><li><a href="/wiki/developers/understanding-cakephp/components">Components</a></li><li><a href="/wiki/developers/understanding-cakephp/models">Models</a></li><li><a href="/wiki/developers/understanding-cakephp/behaviors">Behaviors</a></li><li><a href="/wiki/developers/understanding-cakephp/views">Views</a></li><li><a href="/wiki/developers/understanding-cakephp/helpers">Helpers</a></li></ul></li><li><a href="/wiki/developers/callbacks">Callbacks</a></li><li><a href="/wiki/developers/plugins">Plugins</a><ul><li><a href="/wiki/developers/plugins/file-structure">File structure</a></li><li><a href="/wiki/developers/plugins/yaml-file">YAML file</a></li><li><a href="/wiki/developers/plugins/activation">Activation</a></li><li><a href="/wiki/developers/plugins/bootstrap">Bootstrap</a></li><li><a href="/wiki/developers/plugins/hooks">Hooks</a></li><li><a href="/wiki/developers/plugins/routes">Routes</a></li><li><a href="/wiki/developers/plugins/packaging">Packaging</a></li><li><a href="/wiki/developers/plugins/permissions">Plugin Permissions</a></li></ul></li><li><a href="/wiki/developers/core-plugins">Core plugins</a><ul><li><a href="/wiki/developers/core-plugins/translate">Translate</a></li><li><a href="/wiki/developers/core-plugins/tinymce">TinyMCE</a></li></ul></li><li><a href="/wiki/developers/tips">Tips</a></li></ul></li><li><a href="/wiki/designers">Designers</a><ul><li><a href="/wiki/designers/themes">Themes</a><ul><li><a href="/wiki/designers/themes/file-structure">File structure</a></li><li><a href="/wiki/designers/themes/yaml-file">YAML file</a></li><li><a href="/wiki/designers/themes/basic-layout">Basic layout</a></li><li><a href="/wiki/designers/themes/theme-functions">Theme functions</a></li><li><a href="/wiki/designers/themes/fallback-system">Fallback system</a></li><li><a href="/wiki/designers/themes/packaging">Packaging</a></li></ul></li></ul></li><li><a href="/wiki/translators">Translators</a><ul><li><a href="/wiki/translators/locales">Locales</a><ul><li><a href="/wiki/translators/locales/file-structure">File structure</a></li><li><a href="/wiki/translators/locales/packaging">Packaging</a></li></ul></li></ul></li><li><a href="/wiki/extensions">Extensions</a><ul><li><a href="/wiki/extensions/themes">Themes</a></li><li><a href="/wiki/extensions/plugins">Plugins</a></li></ul></li><li><a href="/wiki/showcase">Showcase</a></li></ul>        </div>
					-->
				    </div>

				<div class="clear"></div>
			    </div>                        </div>
                    </div> 

		    <div class="clear"></div>
                </div> 
            </div> 

            <div class="push"></div> 
        </div> 

        <div id="footer"> 
            <div class="container_16"> 
                <div class="grid_8 footer-left"> 
                    Powered by <a href="/">Croogo</a>.
                </div>

                <div class="clear"></div> 
            </div> 
        </div>
		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-2124770-8']);
		  _gaq.push(['_setDomainName', '.croogo.org']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
    </body>
</html>