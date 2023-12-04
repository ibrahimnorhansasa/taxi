<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripOrderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trip_data;
    /**
     * Create a new event instance.
     */
    public function __construct($trip_data)
    {
        $this->trip_data=$trip_data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('public-channel'),
        ];

    }
    public function broadcastWith(){
        return[
          'data'=> [ 
            'trip_id' =>$this->trip_data->id,
            'user_id'=>$this->trip_data->user_id,
            'fromLate'=>$this->trip_data->fromlate,
            'fromLong'=>$this->trip_data->fromlong,
            'toLate'=>$this->trip_data->tolate,
            'toLong'=>$this->trip_data->tolong,
            'price'=>$this->trip_data->price,
            'status'=>$this->trip_data->status,
            'created'=>$this->trip_data->created_at->format('Y-m-d H:i'),
           ],          
        ];
    }
}
