<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends MyBaseModel
{
    use SoftDeletes;

    /**
     * The rules to validate the model.
     *
     * @var array $rules
     */
    public $rules = [
        'title'              => ['required'],
        'price'              => ['required', 'numeric', 'min:0'],
        'start_sale_date'    => ['date'],
        'end_sale_date'      => ['date', 'after:start_sale_date'],
        'quantity_available' => ['integer', 'min:0'],
    ];

    /**
     * The validation error messages.
     *
     * @var array $messages
     */
    public $messages = [
        'price.numeric'              => 'The price must be a valid number (e.g 12.50)',
        'title.required'             => 'You must at least give a title for your ticket. (e.g Early Bird)',
        'quantity_available.integer' => 'Please ensure the quantity available is a number.',
    ];

    /**
     * The event associated with the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo('\App\Models\Event');
    }

    /**
     * The order associated with the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function order()
    {
        return $this->belongsToMany('\App\Models\Order');
    }

    /**
     * The questions associated with the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions()
    {
        return $this->belongsToMany('\App\Models\Question', 'ticket_question');
    }

    /**
     * TODO:implement the reserved method.
     */
    public function reserved()
    {
    }

    /**
     * Scope a query to only include tickets that are sold out.
     *
     * @param $query
     */
    public function scopeSoldOut($query)
    {
        $query->where('remaining_tickets', '=', 0);
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array $dates
     */
    public function getDates()
    {
        return ['created_at', 'updated_at', 'start_sale_date', 'end_sale_date'];
    }

    /**
     * Get the number of tickets remaining.
     *
     * @return \Illuminate\Support\Collection|int|mixed|static
     */
    public function getQuantityRemainingAttribute()
    {
        if (is_null($this->quantity_available)) {
            return 9999; //Better way to do this?
        }

        return $this->quantity_available - ($this->quantity_sold + $this->quantity_reserved);
    }

    /**
     * Get the number of tickets reserved.
     *
     * @return mixed
     */
    public function getQuantityReservedAttribute()
    {
        $reserved_total = \DB::table('reserved_tickets')
                ->where('ticket_id', $this->id)
                ->where('expires', '>', \Carbon::now())
                ->sum('quantity_reserved');

        return $reserved_total;
    }

    /**
     * Get the booking fee of the ticket.
     *
     * @return float|int
     */
    public function getBookingFeeAttribute()
    {
        return (int) ceil($this->price) === 0 ? 0 : round(($this->price * (config('attendize.ticket_booking_fee_percentage') / 100)) + (config('attendize.ticket_booking_fee_fixed')), 2);
    }

    /**
     * Get the organizer's booking fee.
     *
     * @return float|int
     */
    public function getOrganiserBookingFeeAttribute()
    {
        return (int) ceil($this->price) === 0 ? 0 : round(($this->price * ($this->event->organiser_fee_percentage / 100)) + ($this->event->organiser_fee_fixed), 2);
    }

    /**
     * Get the total booking fee of the ticket.
     *
     * @return float|int
     */
    public function getTotalBookingFeeAttribute()
    {
        return $this->getBookingFeeAttribute() + $this->getOrganiserBookingFeeAttribute();
    }

    /**
     * Get the total price of the ticket.
     *
     * @return float|int
     */
    public function getTotalPriceAttribute()
    {
        return $this->getTotalBookingFeeAttribute() + $this->price;
    }

    /**
     * Get the maximum and minimum range of the ticket.
     *
     * @return array
     */
    public function getTicketMaxMinRangAttribute()
    {
        $range = [];

        for ($i = $this->min_per_person; $i <= $this->max_per_person; $i++) {
            $range[] = [$i => $i];
        }

        return $range;
    }

    /**
     * Indicates if the ticket is free.
     *
     * @return bool
     */
    public function isFree()
    {
        return (int) ceil($this->price) === 0;
    }

    /**
     * Return the maximum figure to go to on dropdowns.
     *
     * @return int
     */
    public function getSaleStatusAttribute()
    {
        if ($this->start_sale_date !== null) {
            if ($this->start_sale_date->isFuture()) {
                return config('attendize.ticket_status_before_sale_date');
            }
        }

        if ($this->end_sale_date !== null) {
            if ($this->end_sale_date->isPast()) {
                return config('attendize.ticket_status_after_sale_date');
            }
        }

        if ((int) $this->quantity_available > 0) {
            if ((int) $this->quantity_remaining <= 0) {
                return config('attendize.ticket_status_sold_out');
            }
        }

        if ($this->event->start_date->lte(\Carbon::now())) {
            return config('attendize.ticket_status_off_sale');
        }

        return config('attendize.ticket_status_on_sale');
    }

//    public function setQuantityAvailableAttribute($value) {
//        $this->attributes['quantity_available'] = trim($value) == '' ? -1 : $value;
//    }
//
//    public function setMaxPerPersonAttribute($value) {
//        $this->attributes['max_per_person'] = trim($value) == '' ? -1 : $value;
//    }
}
