<?php
/**
 * @license see LICENSE
 */

namespace HeadlessChromium;

use HeadlessChromium\Communication\Response;
use HeadlessChromium\Communication\ResponseReader;
use HeadlessChromium\Exception\EvaluationFailed;

/**
 * Used to read data from page evaluation response
 * @internal
 */
class PageEvaluation
{

    /**
     * @var ResponseReader
     */
    protected $responseReader;

    /**
     * @var Response
     */
    protected $response;

    /**
     * PageEvaluation constructor.
     * @param ResponseReader $responseReader
     * @internal
     */
    public function __construct(ResponseReader $responseReader)
    {
        $this->responseReader = $responseReader;
    }

    /**
     * Wait for the script to evaluate and to return a valid response
     */
    public function waitForResponse()
    {
        $response = $this->responseReader->waitForResponse();

        if (!$this->response->isSuccessful()) {
            throw new EvaluationFailed('Could not evaluate the script in the page.');
        }

        $this->response = $response;

        return $this;
    }

    /**
     * Gets the value produced when the script evaluated in the page
     * @return mixed
     * @throws EvaluationFailed
     */
    public function getReturnValue()
    {
        if (!$this->response) {
            $this->waitForResponse();
        }

        return $this->response->getResultData('value');
    }

    /**
     * Gets the return type of the response from the page
     * @return mixed
     * @throws EvaluationFailed
     */
    public function getReturnType()
    {
        if (!$this->response) {
            $this->waitForResponse();
        }

        return $this->response->getResultData('type');
    }
}
