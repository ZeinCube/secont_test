<?php

namespace App\Service;

use App\Client\CommentHttpClient;
use App\Client\HttpMethodEnum;
use App\Model\Comment;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use RuntimeException;

class CommentService
{
    private CommentHttpClient $client;

    public function __construct(string $baseUrl = 'http:://example.com/', array $options = [])
    {
        $this->client = new CommentHttpClient($baseUrl, $options);
    }

    /**
     * @return Comment[]
     */
    public function getCommentList(): array
    {
        try {
            $response = $this->client->sendRequest(HttpMethodEnum::GET, 'comments');
        } catch (GuzzleException $e) {
            throw new RuntimeException('Request invalid: ' . $e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException('Response invalid');
        }

        $response = $response->getBody();

        $response = json_decode($response, true);
        $result = [];

        foreach ($response as $item) {
            $result []= new Comment(
                $item['name'],
                $item['text'],
                $item['id'],
            );
        }

        return $result;
    }

    public function addComment(Comment $comment): Comment
    {
        $body = [
            'name' => $comment->getName(),
            'text' => $comment->getText()
        ];

        $body = json_encode($body);
        try {
            $response = $this->client->sendRequest(HttpMethodEnum::POST, 'comment', [
                RequestOptions::BODY => $body,
            ]);
        } catch (GuzzleException $e) {
            throw new RuntimeException('Request invalid: ' . $e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException('Response invalid');
        }

        $response = json_decode($response->getBody(), true);

        return new Comment(
            $response['name'],
            $response['text'],
            $response['id'],
        );
    }

    public function updateComment(Comment $comment): Comment
    {
        if ($comment->getId() === null) {
            throw new RuntimeException('Id must not be null');
        }

        $body = [
            'name' => $comment->getName(),
            'text' => $comment->getText()
        ];

        $body = json_encode($body);

        $uri = 'comment/' . $comment->getId();

        try {
            $response = $this->client->sendRequest(HttpMethodEnum::PUT, $uri, [
                RequestOptions::BODY => $body,
            ]);
        } catch (GuzzleException $e) {
            throw new RuntimeException('Request invalid: ' . $e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException('Response invalid');
        }

        $response = json_decode($response->getBody(), true);

        return new Comment(
            $response['name'],
            $response['text'],
            $response['id'],
        );
    }
}