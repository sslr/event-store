<?php

/**
 * This file is part of prooph/event-store.
 * (c) 2014-2021 Alexander Miertsch <kontakt@codeliner.ws>
 * (c) 2015-2021 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\EventStore\Internal;

use Prooph\EventStore\EventId;
use Prooph\EventStore\EventStoreSubscription;
use Prooph\EventStore\PersistentSubscriptionNakEventAction;

/** @internal */
class PersistentEventStoreSubscription extends EventStoreSubscription
{
    private ConnectToPersistentSubscriptions $subscriptionOperation;

    public function __construct(
        ConnectToPersistentSubscriptions $subscriptionOperation,
        string $streamId,
        int $lastCommitPosition,
        ?int $lastEventNumber
    ) {
        parent::__construct(
            $streamId,
            $lastCommitPosition,
            $lastEventNumber
        );

        $this->subscriptionOperation = $subscriptionOperation;
    }

    public function unsubscribe(): void
    {
        $this->subscriptionOperation->unsubscribe();
    }

    /** @param list<EventId> $eventIds */
    public function notifyEventsProcessed(array $eventIds): void
    {
        $this->subscriptionOperation->notifyEventsProcessed($eventIds);
    }

    /**
     * @param list<EventId> $eventIds
     */
    public function notifyEventsFailed(
        array $eventIds,
        PersistentSubscriptionNakEventAction $action,
        string $reason
    ): void {
        $this->subscriptionOperation->notifyEventsFailed($eventIds, $action, $reason);
    }
}
