<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentRecord extends Model
{
    /**
     * [$table Contains the talbe name to which this model is going to be bound]
     * @var string
     */
    protected $table = 'payments';

    /**
     * [$timestamps Indicates Laravel whether it should add the created_at and updated_at timestamps to the DB table]
     * @var boolean
     */
    public $timestamps = false;

   	/**
   	 * [$guraded Indicates which of the table parameters cannot be mass assigned. In this case, we're allowing everything to go through]
   	 * @var array
   	 */
    protected $guarded = [];

    /**
     * [$hidden Parameters that won't be returned in a model response once converted to an array]
     * @var array
     */
    protected $hidden = ['id'];
}
