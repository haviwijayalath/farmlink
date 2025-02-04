<?php

class orderControllers extends controllers{

  private $ordercontrol;

  public function address(){
      $this->view('buyer/cart/address');
  }

}