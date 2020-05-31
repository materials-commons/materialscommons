<?php

use Illuminate\Pipeline\Pipeline;

function pipe()
{
    return app(Pipeline::class);
}

