<?php

namespace Zirsakht\Responder;

use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class Responder
{
    protected string $status = 'success';

    protected int $code = Response::HTTP_OK;

    protected string $message;

    protected array $errors = [];

    protected array $headers = [];

    protected mixed $payload = [];

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function setErrors(array $errors): self
    {
        $this->errors = array_merge(
            $this->errors,
            Arr::wrap($errors)
        );

        return $this;
    }

    public function setError(string $code, string $message): self
    {
        $this->setErrors([[
            'code' => $code,
            'message' => $message,
        ]]);

        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = array_merge(
            $this->headers,
            Arr::wrap($headers)
        );

        return $this;
    }

    public function setHeader(string $key, mixed $value): self
    {
        $this->setHeaders(
            array_combine([$key], [$value])
        );

        return $this;
    }

    public function setPayload(mixed $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    protected function toArray(): array
    {
        $response = [
            'status' => $this->status,
            'code' => $this->code,
        ];

        if (!empty($this->message)) {
            $response['message'] = $this->message;
        }

        if (!empty($this->errors)) {
            $response['errors'] = $this->errors;
        }

        if (!empty($this->payload)) {
            $response['payload'] = $this->payload;
        }

        return [$response, $this->code, $this->headers];
    }

    protected function send(string $format = 'preferred')
    {
        return call_user_func_array(
            [response(), $format],
            $this->toArray()
        );
    }

    public function success(): self
    {
        return $this->setStatus('success');
    }

    public function fail(): self
    {
        return $this->setStatus('fail');
    }

    public function error(): self
    {
        return $this->setStatus('error');
    }

    public function xml()
    {
        return $this->send('xml');
    }

    public function json()
    {
        return $this->send('json');
    }

    public function preferred()
    {
        return $this->send('preferred');
    }
}
