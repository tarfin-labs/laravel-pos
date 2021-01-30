<?php

namespace TarfinLabs\LaravelPos;

class LaravelPos
{
    public function builder(){
        return new PaymentBuilder();
    }
}
