<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
/**
 * Enable routing for the api to route to domain.com/api/controller/action.json
 */
	Router::connect('/api/:controller/:action/*', array('prefix' => 'api', 'api' => true));

/**
 *  Template routes
*/
	Router::connect('/admin/cobrands/:parent_controller_id/templates',
		array('controller' => 'templates', 'action' => 'index', 'prefix' => 'admin', 'admin' => true));
	Router::connect('/admin/cobrands/:parent_controller_id/templates/:action/*',
		array('controller' => 'templates', 'action' => null, 'prefix' => 'admin', 'admin' => true));

	Router::connect('/admin/templates/:parent_controller_id/templatepages',
		array('controller' => 'templatePages', 'action' => 'index', 'prefix' => 'admin', 'admin' => true));
	Router::connect('/admin/templates/:parent_controller_id/templatepages/:action/*',
		array('controller' => 'templatePages', 'action' => null, 'prefix' => 'admin', 'admin' => true));

	Router::connect('/admin/templatepages/:parent_controller_id/templatesections',
		array('controller' => 'templateSections', 'action' => 'index', 'prefix' => 'admin', 'admin' => true));
	Router::connect('/admin/templatepages/:parent_controller_id/templatesections/:action/*',
		array('controller' => 'templateSections', 'action' => null, 'prefix' => 'admin', 'admin' => true));

	Router::connect('/admin/templatesections/:parent_controller_id/templatefields',
		array('controller' => 'templateFields', 'action' => 'index', 'prefix' => 'admin', 'admin' => true));
	Router::connect('/admin/templatesections/:parent_controller_id/templatefields/:action/*',
		array('controller' => 'templateFields', 'action' => null, 'prefix' => 'admin', 'admin' => true));

	Router::connect('/admin/applications/:action/*',
		array('controller' => 'cobranded_applications', 'admin' => true));
	Router::connect('/admin/applications',
		array('controller' => 'cobranded_applications', 'admin' => true));
	Router::connect('/applications/document_callback',
		array('controller' => 'cobranded_applications', 'action' => 'document_callback', 'admin' => false));


/**
 * Allow JSON extensions to be properly parsed
 */
	Router::parseExtensions('json', 'xml');
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
