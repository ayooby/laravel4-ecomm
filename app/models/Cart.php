<?php
Class Cart extends Eloquent {

protected $table = 'carts';

protected $fillable = array('member_id','name','price','quantity' ,'product_id' ,'image');

public function product(){

return $this->belongsTo('Product');

}

}