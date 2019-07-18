<?php

function setActiveNav($path)
{
    return Request::is('app/' . $path . '*') ? 'active' : '';
}

function setActiveNavByName($name) {
    return Request::routeIs('app.' . $name . '*') ? 'active' : '';
}
