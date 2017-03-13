<?php
/**
 * Copyright 2016, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2016, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\Api\Service\Renderer;

use CakeDC\Api\Service\Action\Result;
use Cake\Core\Configure;
use Exception;

/**
 * Class RawRenderer
 * Raw unformatted content negotiation Renderer.
 *
 * @package CakeDC\Api\Service\Renderer
 */
class RawRenderer extends BaseRenderer
{

    /**
     * Builds the HTTP response.
     *
     * @param Result $result The result object returned by the Service.
     * @return bool
     */
    public function response(Result $result = null)
    {
        $response = $this->_service->response();
        $response->withStatus($result->code());
        $response->type('text/plain');
        $body = $response->getBody();
        $body->rewind();
        $body->write((string)$result->data());
        $response->withBody($body);

        return true;
    }

    /**
     * Processes an exception thrown while processing the request.
     *
     * @param Exception $exception The exception object.
     * @return void
     */
    public function error(Exception $exception)
    {
        $response = $this->_service->response();
        $response->type('text/plain');
        $message = (Configure::read('debug') > 0) ? $exception->getMessage() . ' on line ' . $exception->getLine() . ' in ' . $exception->getFile() : $exception->getMessage();
        $trace = $exception->getTrace();
        $debug = (Configure::read('debug') > 0) ? "\n" . print_r($trace, true) : '';
        $body = $response->getBody();
        $body->rewind();
        $body->write($message . $debug);
        $response->withBody($body);
    }
}
