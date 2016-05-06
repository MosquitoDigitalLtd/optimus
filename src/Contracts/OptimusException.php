<?php

namespace Appitized\Optimus\Contracts;

interface OptimusException
{
    public function display(array $headers = []);
}
