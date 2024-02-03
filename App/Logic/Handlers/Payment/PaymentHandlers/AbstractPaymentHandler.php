<?php

namespace App\Logic\Handlers\Payment\PaymentHandlers;

use App\Logic\Models\GatewayModel;
use Closure;
use Sim\Event\Emitter;
use Sim\Event\Event;
use Sim\Event\EventProvider;

abstract class AbstractPaymentHandler implements PaymentHandlerInterface
{
    /**
     * @var GatewayModel
     */
    protected $gatewayModel = null;

    /**
     * @var array
     */
    protected array $credentials = [];

    /*----------------------------------------------------------
     | Event Variables
     ----------------------------------------------------------*/

    const EVENT_CONNECTION_SUCCESS = 'connection:success';
    const EVENT_CONNECTION_FAILED = 'connection:failed';
    const EVENT_RESULT_SUCCESS = 'result:success';
    const EVENT_RESULT_FAILED = 'result:failed';

    /**
     * @var Emitter
     */
    protected Emitter $emitter;

    /**
     * @var EventProvider
     */
    protected EventProvider $eventProvider;

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
        $this->gatewayModel = container()->get(GatewayModel::class);
        //
        $this->eventProvider = new EventProvider();
        $this->eventProvider->addEvent(new Event(self::EVENT_CONNECTION_SUCCESS));
        $this->eventProvider->addEvent(new Event(self::EVENT_CONNECTION_FAILED));
        $this->eventProvider->addEvent(new Event(self::EVENT_RESULT_SUCCESS));
        $this->eventProvider->addEvent(new Event(self::EVENT_RESULT_FAILED));
        //
        $this->emitter = new Emitter($this->eventProvider);
    }

    /**
     * @inheritDoc
     */
    public function onSuccessConnectionEvent(Closure $closure)
    {
        $this->emitter->addListener(self::EVENT_CONNECTION_SUCCESS, $closure);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function onFailedConnectionEvent(Closure $closure)
    {
        $this->emitter->addListener(self::EVENT_CONNECTION_FAILED, $closure);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function onSuccessResultEvent(Closure $closure)
    {
        $this->emitter->addListener(self::EVENT_RESULT_SUCCESS, $closure);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function onFailedResultEvent(Closure $closure)
    {
        $this->emitter->addListener(self::EVENT_RESULT_FAILED, $closure);
        return $this;
    }

    /**
     * @param $type
     * @param $code
     * @param $msg
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    protected static function logConnectionError($type, $code, $msg)
    {
        logger_gateway()->error([
            'section' => 'product',
            'gateway_type' => $type,
            'code' => $code,
            'message' => $msg,
        ]);
    }
}
