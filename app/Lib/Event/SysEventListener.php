<?php

App::uses('CakeEventListener', 'Event');
App::uses('EventType', 'Model');

class SysEventListener extends Object implements CakeEventListener {

/**
 * implementedEvents
 *
 * @return array
 */
	public function implementedEvents() {
		return array(
			'Auth.afterIdentify' => 'logUserLogInEvent',
		);
	}

/**
 * Create a system transaction
 *
 * The $event->subject will be the model that has been updated
 *
 * @param object $event Event
 * @return void
 * @throws RuntimeException when the transaction can not be saved to the database
 * @throws InvalidArgumentException
 * @todo: implement logic for all transaction types
 */
	public function logUserLogInEvent($event) {
		$userId = Hash::get($event->data, 'user.id');
		if (empty($userId)) {
			throw new InvalidArgumentException(__('Invalid user id'));
		}
		$logData = array(
			'event_type_id' => EventType::USER_LOG_IN_ID,
			'user_id' => $userId,
			'client_ip' => Router::getRequest()->clientIp()
		);
		$this->_logEvent($logData);
	}

/**
 * _logEvent
 * Utility method to save event data
 *
 * @param array $eData single dimension array containing SysEventLog fields as keys and their corresponding values
 *
 */
	protected function _logEvent($eData) {
		$SysEventLog = ClassRegistry::init('SysEventLog');
		$SysEventLog->create();
		if (!$SysEventLog->save(array('SysEventLog' => $eData))) {
			$this->log('Error logging system event');
			$this->log($eData);
			$this->log($SysEventLog->validationErrors);
		}
	}
}
