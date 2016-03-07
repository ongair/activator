<?php

  require 'vendor/autoload.php';

  $app = new \Slim\Slim();
  $app->view(new \JsonApiView());
  $app->add(new \JsonApiMiddleware());

  // Request a code
  $app->post('/request', function() use ($app) {
    
    $username = $app->request->params('phone_number');
    $name = $app->request->params('nickname');
    $mode = $app->request->params('mode');

    if ($mode == null || $mode == '')
      $mode = 'sms';

    // handler for the message
    $message = '';
    $success = true;
    $error = false;

    try {
      // create the client
      $w = new WhatsProt($username, $nickname, false);
      
      // request a code
      $response = $w->codeRequest(trim($mode));
    }
    catch(Exception $ex) {
      $message = $ex->getMessage();
      $success = false;
    }

    $app->render(200, array(
      'success' => $success,
      'message' => $message
    ));

  });

  $app->run();