<?php

nc_core::get_object()->event->add_listener(nc_event::AFTER_MODULES_LOADED, function() {
    include 'rbkmoney.php';
});