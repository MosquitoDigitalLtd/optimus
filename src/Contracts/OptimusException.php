<?php

namespace Appitized\Optimus\Exceptions;

interface OptimusException
{
    public function display(array $headers = []);
}
