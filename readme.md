# Using this library

This library is licensed with the MIT license.

Add the library to your `composer.json`:

> composer require dapr/php-sdk

# Accessing Secrets

You can access secrets easily:

```php
<?php

use Dapr\Secret;

echo Secret::retrieve('my_secret_store', 'secret_name');
// output: my-secret
```

# Accessing State

```php
<?php

use Dapr\State\State;

class MyState extends State {
    /**
     * @var string
     */
    public $string_value;

    /**
     * @var array
     */
    public $complex_type;

    /**
     * @var Exception
     */
    public $object_type;

    /**
     * @var int
     */
    public $counter = 0;

    /**
     * Initialize the state
     */
    public function __construct() {
        parent::__construct('name_of_state_store');
    }

    /**
     * Increment the counter
     * @param int $amount Amount to increment by
     */
    public function increment($amount = 1): void {
        $this->counter += $amount;
    }
}

// use state objects
$state = new MyState();
$state->load();
echo $state->string_value;
$state->string_value = 'hello world';
$state->save_state();

// load individual state
$value = State::get_single('name_of_state_store', 'string_value');
echo $value->string_value;
```

## Transactional State

You can also use transactional state to interact with state objects.

```php
use Dapr\consistency\StrongFirstWrite;
use Dapr\exceptions\StateAlreadyCommitted;
use Dapr\State\TransactionalState;

/*
 * Return a transactional state of the MyState type, with strong, first-write consistency
 * @var MyState $transaction_state
 */
$state = TransactionalState::begin(MyState::class, 'name_of_state_store', new StrongFirstWrite);

// state is loaded and the transaction is started for you
echo $state->string_value;

// we can change state
$state->string_value = 'new value';

// even via a helper function
$state->increment(2);

// calling delete is like calling unset(), these are both the same operation.
$state->delete('object_type');
unset($state->object_type);

// once we're happy with our state, we can commit
TransactionalState::commit($state);

// once state is committed, state becomes read-only. All the following would throw.
try {
    echo $state->string_value;
    $state->string_value = 'failed';
    $state->delete('object_type');
    $state->commit();
}  catch (StateAlreadyCommitted $ex) {
    echo "Cannot alter already committed state!";
}
```

# Actors

Actors are fully implemented and quite powerful. In order to define an actor, you must first define the interface. You'll likely want to put this in a separate library
for easy calling from other services.

```php
<?php

use Dapr\Actors\IActor;

/**
 * Actor that keeps a count
 */
interface ICounter extends IActor {
    public const DAPR_TYPE = 'Counter';

    /**
     * Increment a counter
     */
    public function increment($amount);
}
```

Once the interface is defined, you'll need to implement the behavior and register the actor.

```php
<?php

use Dapr\Actors\{Actor,ActorRuntime,ActorState};
use Dapr\consistency\StrongFirstWrite;

class Counter implements ICounter {
    use Actor;
    use ActorState;

    public const STATE_TYPE = [
        'store' => 'statestore',
        'type' => CountState::class,
        'consistency' => StrongFirstWrite::class,
        'metadata' => []
    ];

    /**
     * @var int
     */
    private $id;

    /**
     * @var CountState
     */
    private $state;

    /**
     * Initialize the class
     */
    public function __construct($id, $state) {
        $this->id = $id;
        $this->state = $state;
    }

    /**
     * Increment the count by 1
     */
    public function increment($amount) {
        $this->state->count += $amount;
    }

    /**
     * Handle a scheduled reminder from dapr
     * @param string $name The name of the reminder
     * @param mixed $data The data
     */
    public function remind($name, $data) {
        switch($name) {
            case 'increment':
                $this->increment($data['amount'] ?? 1);
                break;
        }
    }

    /**
     * Handle any special activation logic
     */
    public function on_activation() {}

    /**
     * Handle any special deactivation logic
     */
    public function on_deactivation() {}
}

// register the actor with the runtime
ActorRuntime::register_actor(dapr_type: 'Counter', actor_type: Counter::class);
```

There are two crucial traits: `Actor` and `ActorState`. If you include `ActorState`, then you'll have a second parameter
passed to your constructor which is the state object, you must include a `DAPR_TYPE` constant in the interface for
determining the type of state for Dapr to understand. State is automatically saved for you if you make any changes to it
during the method call using transactional state.

The `Actor` trait gives you access to some helper functions and implements most of `IActor`:

`function create_reminder(string $name, DateInterval $due_time, DateInterval $period, $data)`:

This allows you to create a durable reminder which the runtime will call even if your actor is deactivated. You're
required to implement the function `handle_reminder($name, $data)`, which is enforced via the `IActor` interface.

`function get_reminder(string $name)`:

Get information about a reminder.

`function delete_reminder(string $name)`:

Delete a reminder.

`function create_timer(string $name, DateInterval $due_time, DateInterval $period, $callback_method, $data)`:

Registers a non-durable callback (meaning if the actor deactivates, the timer is lost). You're responsible for setting
up any timers you need on activation.

`function delete_timer(string $name)`:

Deletes a timer.

## Calling an Actor

In order to call an actor, simply call the `ActorProxy` and get a proxy object:

```php
<?php
use Dapr\Actors\ActorProxy;

/**
 * @var Counter
 */
$counter = ActorProxy::get(ICounter::class, $id);
$counter->increment();
$counter->create_reminder('increment', new DateInterval('PT10M'), new DateInterval('P1D'), ['amount' => 100]);
```

## Actor Limitations

1. There's no re-entrance to an actor, this can cause deadlocks if you're not careful.
2. By design, static functions don't work.
3. There's overhead cost in calling "getter" functions.

More detail here: https://docs.dapr.io/developing-applications/building-blocks/actors/actors-overview/

# Pub/Sub

Delivering events around your application is an important aspect of any application. This is supported by Dapr, and
implemented in this SDK.

## Publishing

In order to publish an event, you need only to call the `Publish` class:

```php
<?php

$publisher = new \Dapr\PubSub\Publish('my_pubsub');
$result = $publisher->topic('my_topic')->publish([
    'message' => 'arrive at dawn'
]);
if($result === false) {
    // handle failure
}
```

## Subscribing

```php
\Dapr\PubSub\Subscribe::to_topic('pubsub', 'my-topic', function(\Dapr\PubSub\CloudEvent $event) { /* do work */ });
```

### Ingesting Events

There's a simple, non-exhaustive, `Dapr\CloudEvent` class that will take a raw json string and return a Cloud Event. You
can also use any other library if you so choose.

```php
<?php

// create an event
$event = new \Dapr\PubSub\CloudEvent();
$event->id = uniqid();
$event->source = $_SERVER['HTTP_HOST'];
$event->type = 'com.myservice.registered.user';
$event->subject = 'user123';
$event->time = new DateTime();
$event->data = [
    'prop' => 'value'
];
(new \Dapr\PubSub\Publish('pubsub'))->topic('user_registrations')->publish($event);
```

When sending an event already as a CloudEvent, Dapr will pass it along to your application unchanged, but will remove
the time.

# Serializing

If you need to register a custom serializer, you can completely override the built-in serializers on a per-type basis or
even the default serializers:

```php
<?php

function do_serialize(MyType $obj): array {
    // do serializing here
}

// ::register takes any callable
\Dapr\Serializer::register('do_serialize', [MyType::class]);
\Dapr\Deserializer::register([MyDeserializer::class, 'deserialize'], [MyType::class]);
```

If you want to override serializing completely, just pass `null` as the types array.

# Methods

Registering a method is fairly straightforward. If you want to set a specific response code, return an array containing
a `code` key and it will be returned to the handler.

```php
\Dapr\Runtime::register_method('my-method', function($name, $birthday) { /* do work */ });
```

We can invoke methods like:

```php
\Dapr\Runtime::invoke_method('app-id', 'my-method', ['name' => 'Rob', 'birthday' => '01-07']);
```

# Bindings

You're probably starting to notice a pattern here, but registering an input binding is also pretty simple:

```php
\Dapr\Binding::register_input_binding('my-input-binding', function($data) {});
```

Invoking an output binding:

```php
$result = \Dapr\Binding::invoke_output('my-output-binding', 'operation', data: ['some' => 'data']);
$success = $result->code === 200;
```

# Project setup

You'll want to configure your server to route all traffic to a certain php file (maybe `index.php`) and then pass on the
request to the runtime:

```php
header('Content-Type: application/json');
\Dapr\Actors\ActorRuntime::register_actor('Counter', Counter::class);
\Dapr\PubSub\Subscribe::to_topic('pubsub', 'my-topic', [TopicHandler::class, 'handle']);
\Dapr\Binding::register_input_binding('my-input-binding', [BindingHandler::class, 'handle']);
\Dapr\Runtime::register_method('my-method', 'handleMyMethod');
$handler = \Dapr\Runtime::get_handler_for_route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
// you can potentially wrap the handler with any filters, for example: displaying pretty json when a browser is involved
$result = $handler();
http_response_code($result['code']);
if(isset($result['body'])) {echo $result['body'];}
die();
```

# Development

Simply run `composer start` on a machine where `dapr init` has already been run. This will start the daprd service on
the current open terminal. Then navigate to [http://localhost:9502/](http://localhost:9502/) to let the integration
tests run.

# Tests

Simply run `composer test` to run the unit tests. You can lint using `composer lint`