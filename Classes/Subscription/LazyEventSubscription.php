<?php
/**
 * Copyright 2020 Martin Neundorfer (Neunerlei)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Last modified: 2020.03.02 at 11:01
 */

namespace Neunerlei\EventBus\Subscription;


use Neunerlei\EventBus\EventBusInterface;

class LazyEventSubscription implements EventSubscriptionInterface {
	
	/**
	 * @var \Neunerlei\EventBus\EventBusInterface
	 */
	protected $bus;
	
	/**
	 * @var callable
	 */
	protected $factory;
	
	/**
	 * LazyEventSubscription constructor.
	 *
	 * @param \Neunerlei\EventBus\EventBusInterface $bus
	 * @param callable                              $factory
	 */
	public function __construct(EventBusInterface $bus, callable $factory) {
		$this->bus = $bus;
		$this->factory = $factory;
	}
	
	/**
	 * @inheritDoc
	 */
	public function subscribe($events, string $method, array $options = []): EventSubscriptionInterface {
		$this->getBus()->addListener($events, function () use ($method) {
			return call_user_func_array([call_user_func($this->factory), $method], func_get_args());
		}, $options);
		return $this;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getBus(): EventBusInterface {
		return $this->bus;
	}
	
}