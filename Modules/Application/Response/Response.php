<?php

class Response
{

    const SUCCESS = 200;

    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;

    /** @var int */
    protected $status;

    /** @var string */
    protected $message;

    /** @var array */
    protected $data = [];

    /**
     * Status code
     * @param int $status
     */
    public function setStatusCode($status): void
    {
        $this->status = $status;
    }

    /**
     * Response message
     * @param string $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    public function __toString()
    {
        return json_encode([
            'status'  => $this->status,
            'message' => $this->message,
            'data'    => $this->data,
        ]);
    }

}