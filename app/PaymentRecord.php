<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentRecord extends Model
{
    /**
     * [$table description]
     * @var string
     */
    protected $table = 'payments';

    /**
     * [$timestamps description]
     * @var boolean
     */
    public $timestamps = false;

   	/**
   	 * [$guraded description]
   	 * @var [type]
   	 */
    protected $guarded = [];

    /**
     * [$hidden description]
     * @var array
     */
    protected $hidden = ['id'];
}
