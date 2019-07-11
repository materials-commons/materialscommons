<?php

function setActiveNav($path)
{
    return Request::is('app/' . $path . '*') ? 'active' : '';
}
