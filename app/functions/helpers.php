<?php

function path()
  {
      $vendorDir = dirname(dirname(__FILE__));

      return dirname($vendorDir);
  }

  
function error($message) {
	return "<span class='card bg-danger text-white shadow'>* {$message} </span>";
}

function success($message) {
	return "<span class='card bg-success text-white shadow'>{$message}</span>";
}
