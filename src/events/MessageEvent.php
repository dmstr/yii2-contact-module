<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\contact\events;

use yii\base\Event;

/**
 * Class MessageEvent
 * @package dmstr\modules\contact\events
 * @author Marc Mautz <m.mautz@herzogkommunikation.de>
 */
class MessageEvent extends Event
{
    const EVENT_BEFORE_MESSAGE_SENT = 'eventBeforeMessageSent';
    const EVENT_AFTER_MESSAGE_SENT = 'eventAfterMessageSent';
    const EVENT_SENT_MESSAGE_ERROR = 'eventSentMessageError';

    public $model;
}
