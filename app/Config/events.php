<?php
App::uses('ClassRegistry', 'Utility');
App::uses('SysEventListener', 'Lib/Event');
App::uses('CakeEventManager', 'Event');

// Global events
CakeEventManager::instance()->attach(new SysEventListener());

/**
 * To attach an event listener to a model, add it to the 'ModelEventListeners' configuration.
 * The AppModel method '_attachListeners' will attach them in the model's constructor.
 *
 * This will make it easy to attach/detach mocked listeners for unit tests
 * Example:
 * Configure::write('ModelEventListeners', [
 *		'<ModelName>' => [
 *		'EventHandlerName' => new EventHandlerName(),
 *		],
 *	]);
 * 
 */
