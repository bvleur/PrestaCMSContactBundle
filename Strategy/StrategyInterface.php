<?php
/**
 * This file is part of the PrestaCMSContactBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSContactBundle\Strategy;

use Presta\CMSContactBundle\Model\Message;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
interface StrategyInterface
{
    /**
     * Handle message submission
     *
     * @param  Message $message
     * @param  array $options Options array with possible keys: "source", "email_from", "email_to".
     */
    public function handle(Message $message, $options);
}
