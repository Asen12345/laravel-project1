<?php

namespace App\Session;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Query\Builder;
use Illuminate\Session\ExistenceAwareInterface;
use Illuminate\Support\Arr;
use SessionHandlerInterface;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\QueryException;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Container\Container;

class CustomSessionHandler implements SessionHandlerInterface, ExistenceAwareInterface
{
    use InteractsWithTime;

    /**
     * The database connection instance.
     *
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * The name of the session table.
     *
     * @var string
     */
    protected $table;

    /**
     * The number of minutes the session should be valid.
     *
     * @var int
     */
    protected $minutes;

    /**
     * The container instance.
     *
     * @var Container
     */
    protected $container;

    /**
     * The existence state of the session.
     *
     * @var bool
     */
    protected $exists;

    /**
     * Create a new database session handler instance.
     *
     * @param ConnectionInterface $connection
     * @param  string  $table
     * @param  int  $minutes
     * @param Container|null  $container
     * @return void
     */
    public function __construct(ConnectionInterface $connection, $table, $minutes, Container $container = null)
    {
        $this->table = $table;
        $this->minutes = $minutes;
        $this->container = $container;
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        $session = (object) $this->getQuery()->find($sessionId);

        if ($this->expired($session)) {
            $this->exists = true;

            return '';
        }

        if (isset($session->payload)) {
            $this->exists = true;

            return base64_decode($session->payload);
        }

        return '';
    }

    /**
     * Determine if the session is expired.
     *
     * @param  \stdClass  $session
     * @return bool
     */
    protected function expired($session)
    {
        return isset($session->last_activity) &&
            $session->last_activity < Carbon::now()->subMinutes($this->minutes)->getTimestamp();
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        $payload = $this->getDefaultPayload($data, $sessionId);

        if (! $this->exists) {
            $this->read($sessionId);
        }

        if ($this->exists) {
            $this->performUpdate($sessionId, $payload);
        } else {
            $this->performInsert($sessionId, $payload);
        }
        return $this->exists = true;
    }

    /**
     * Perform an insert operation on the session ID.
     *
     * @param  string  $sessionId
     * @param  string  $payload
     * @return bool|null
     */
    protected function performInsert($sessionId, $payload)
    {

        try {
            return $this->getQuery()->insert(Arr::set($payload, 'id', $sessionId));
        } catch (QueryException $e) {
            $this->performUpdate($sessionId, $payload);
        }
    }

    /**
     * Perform an update operation on the session ID.
     *
     * @param  string  $sessionId
     * @param  string  $payload
     * @return int
     */
    protected function performUpdate($sessionId, $payload)
    {
        $payload = Arr::except($payload, 'start_data');
        return $this->getQuery()->where('id', $sessionId)->update($payload);
    }

    /**
     * Get the default payload for the session.
     *
     * @param  string  $data
     * @return array
     */
    protected function getDefaultPayload($data, $sessionId)
    {
        $payload = [
            'payload'       => base64_encode($data),
            'last_activity' => $this->currentTime(),
            'role'          => $this->getRole(),
            'start_data'    => $this->getStartTime(),
        ];

        if(auth()->check()){
            auth()->user()->update([
                'last_login_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ]);
        }


        if (! $this->container) {
            return $payload;
        }

        return tap($payload, function (&$payload) {
            $this->addUserInformation($payload)
                ->addRequestInformation($payload);
        });
    }

    /**
     * Add the user information to the session payload.
     *
     * @param array $payload
     * @return $this
     * @throws BindingResolutionException
     */
    protected function addUserInformation(&$payload)
    {
        if ($this->container->bound(Guard::class)) {
            $payload['user_id'] = $this->userId();
        }

        return $this;
    }

    /**
     * Get the currently authenticated user's ID.
     *
     * @return mixed
     * @throws BindingResolutionException
     */
    protected function userId()
    {
        return $this->container->make(Guard::class)->id();
    }

    /**
     * Add the request information to the session payload.
     *
     * @param array $payload
     * @return $this
     * @throws BindingResolutionException
     */
    protected function addRequestInformation(&$payload)
    {
        if ($this->container->bound('request')) {
            $payload = array_merge($payload, [
                'ip_address' => $this->ipAddress(),
                'user_agent' => $this->userAgent(),
            ]);
        }

        return $this;
    }

    /**
     * Get the IP address for the current request.
     *
     * @return string
     * @throws BindingResolutionException
     */
    protected function ipAddress()
    {
        return $this->container->make('request')->ip();
    }

    /**
     * Get the user agent for the current request.
     *
     * @return string
     * @throws BindingResolutionException
     */
    protected function userAgent()
    {
        return substr((string) $this->container->make('request')->header('User-Agent'), 0, 500);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId )
    {

        $this->getQuery()->where('id', $sessionId)->delete();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        $this->getQuery()->where('last_activity', '<=', $this->currentTime() - $lifetime)->delete();
    }

    /**
     * Get a fresh query builder instance for the table.
     *
     * @return Builder
     */
    protected function getQuery()
    {
        return $this->connection->table($this->table);
    }

    /**
     * Set the existence state for the session.
     *
     * @param  bool  $value
     * @return $this
     */
    public function setExists($value)
    {
        $this->exists = $value;

        return $this;
    }


    /*Get role column if User or Admin Authorize*/
    protected function getRole() {

        return ((auth()->check()) ? auth()->user()->role : null);

    }

    private function getStartTime()
    {
        return Carbon::now();
    }
}
