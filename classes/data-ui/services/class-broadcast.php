<?php

namespace Data_UI\Services;

/**
 * Class UI
 *
 * @package Data_UI
 */
class Broadcast {

    /**
     * Holds the broadcast ID.
     *
     * @var string
     */
    protected $broadcast_id;

    /**
     * Holds the subscribed events and callbacks.
     *
     * @var array
     */
    protected $subscriptions = array();

    /**
     * Broadcast constructor.
     *
     * @param string $broadcast_id The broadcast/slug to connect to.
     */
    public function __construct( $broadcast_id ) {
        $this->broadcast_id = $broadcast_id;
        add_action( 'init', array( $this, 'register_subscriptions' ) );
    }

    /**
     * Get the event name for this broadcast.
     *
     * @param string $event Event name to get.
     *
     * @return string
     */
    protected function get_event_name( $event ) {
        return "broadcast_event_{$this->broadcast_id}_{$event}";
    }

    /**
     * Subscribe to an event.
     *
     * @param $event
     * @param $callback
     * @param $priority
     */
    public function subscribe( $event, $callback, $priority = 10 ) {
        $this->subscriptions[ $event ][] = array(
            'callback' => $callback,
            'priority' => $priority,
        );
    }

    /**
     * Unsubscribe to an event.
     *
     * @param string   $event    The event to unsubscribe from.
     * @param callable $callback The callback to remove.
     */
    public function unsubscribe( $event, $callback ) {
        $subscriptions = $this->subscriptions[ $event ];
        if ( ! empty( $subscriptions ) ) {
            foreach ( $subscriptions as $index => $subscription ) {
                if ( $callback === $subscription['callback'] ) {
                    unset( $this->subscriptions[ $event ][ $index ] );
                }
            }
        }
    }

    /**
     * Register subscriptions.
     */
    public function register_subscriptions() {
        foreach ( $this->subscriptions as $event => $subscriptions ) {
            foreach ( $subscriptions as $subscription ) {
                add_action( $this->get_event_name( $event ), $subscription['callback'], $subscription['priority'], 3 );
            }
        }
    }

    /**
     * Broadcast an event.
     *
     * @param string     $event Event name to broadcast.
     * @param mixed|null $data  The event data to broadcast.
     */
    public function event( $event, $data = null ) {
        /**
         * Broadcast global type event.
         *
         * @param self       $broadcaster The object broadcasting the event.
         * @param string     $event       The event name.
         * @param mixed|null $data        The event data to broadcast.
         */
        do_action( "broadcast_event", $this, $event, $data );
        /**
         * Broadcast global type event.
         *
         * @param mixed|null $data        The event data to broadcast.
         * @param self       $broadcaster The object broadcasting the event.
         */
        do_action( $this->get_event_name( $event ), $data, $this );
    }
}
