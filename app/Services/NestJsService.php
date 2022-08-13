<?php
namespace App\Services;

use Illuminate\Redis\RedisManager;
use Illuminate\Support\Str;
use Predis\PubSub\Consumer;
use Illuminate\Support\Collection;

class NestJsService
{
  /** @var RedisManager $redis */
  protected $redis;

  function __construct(RedisManager $redis)
  {
    $this->redis = $redis;
  }

  /*---------------------------------------------------------------------*
      PUBLIC METHODS
    *---------------------------------------------------------------------*/
  public function send($pattern, $data = null)
  {
    // Build the payload object from the params
    $payload = $this->newPayload($pattern, $data);
    // Make a call to NestJS with the payload &
    // return the response.
    return $this->callNestMicroservice($payload);
  }

  /*---------------------------------------------------------------------*
      INTERNAL METHODS
    *---------------------------------------------------------------------*/
  /**
  * Create new UUID
  *
  * @return string
  */
  protected function newUuid()
  {
    return Str::uuid()->toString();
  }

  /**
  * Create new collection
  *
  * @return Collection
  */
  protected function newCollection()
  {
    return collect();
  }

  /**
  * Create new payload array
  *
  * @param string $pattern
  * @param mixed $data
  * @return array
  */
  protected function newPayload($pattern, $data) {
    return [
      'id' => $this->newUuid(),
      'pattern' => json_encode($pattern),
      'data' => $data,
    ];
  }

  /**
  * Make request to microservice
  *
  * @param array $payload
  * @return Collection
  */
  protected function callNestMicroservice($payload)
  {
    $uuid = $payload['id'];
    $pattern = $payload['pattern'];
    // Subscribe to the response channel
    /** @var Consumer $loop */
    $loop = $this->redis->connection('pubsub')
            ->pubSubLoop(['subscribe' => "{$pattern}_res"]);

    // Send payload across the request channel
    $this->redis->connection('default')
          ->publish("{$pattern}_ack", json_encode($payload));
    // Create a collection to store response(s); there could be multiple!
    // (e.g., if NestJS returns an observable)
    $result = $this->newCollection();

    // Loop through the response object(s), pushing the returned vals into
    // the collection.  If isDisposed is true, break out of the loop.
    foreach ($loop as $msg) {
      if ($msg->kind === 'message') {
        $res = json_decode($msg->payload);

        if ($res->id === $uuid) {
          $result->push($res->response);
          if (property_exists($res, 'isDisposed') && $res->isDisposed) {
            $loop->stop();
          }
        }
      }
    }
    return $result; // return the collection
  }
}
