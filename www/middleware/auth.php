<?php

if (!$auth->check()) {
    $auth->autoLogin();
}