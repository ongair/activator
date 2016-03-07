<?php

  require 'vendor/autoload.php';

  $app = new \Slim\Slim();
  $app->view(new \JsonApiView());
  $app->add(new \JsonApiMiddleware());

  // Request a code
  $app->post('/request', function() use ($app) {
    
    $username = $app->request->params('phone_number');
    $mode = $app->request->params('mode');
    $retry_after = 1805;

    if ($mode == null || $mode == '')
      $mode = 'sms';

    // handler for the message
    $message = '';
    $success = true;
    $error = false;
    $rsp = array();

    try {
      // create the client
      $w = new Registration($username, false);
      
      // request a code
      $response = $w->codeRequest($mode);
      $message = 'Code requested';            

      $rsp = array(
        'retry_after' => $response->retry_after,
        'method' => $response->method,
        'reason' => $response->reason,
        'param' => $response->param,
        'status' => $response->status,
        'length' => $response->length
      );
    }
    catch(Exception $ex) {
      $message = $ex->getMessage();
      $success = false;
    }

    $app->render(200, array(
      'success' => $success,
      'message' => $message,
      'response' => $rsp
    ));

  });

  $app->get('/status', function() use ($app) {
    $app->render(200, array(
      'success' => true
    ));
  });

  $app->run();