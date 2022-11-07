<?php

namespace Test;

require '../vendor/autoload.php';

use App\Model\Comment;
use App\Service\CommentService;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testCheckList(): void
    {
        $response = [
            [
                'id' => 1,
                'name' => 'first',
                'text' => 'first text',
            ],
            [
                'id' => 2 ,
                'name' => 'second',
                'text' => 'second text',
            ],
        ];

        $body = json_encode($response);
        $mock = new MockHandler([new Response(body: $body)]);
        $handler = HandlerStack::create($mock);
        $commentService = new CommentService(options: ['handler' => $handler]);

        $list = $commentService->getCommentList();

        $this->assertIsArray($list);
    }

    public function testAdd(): void
    {
        $response = [
            'id' => 3,
            'name' => 'new comment',
            'text' => 'new text',
        ];
        $body = json_encode($response);

        $mock = new MockHandler([new Response(body: $body)]);
        $handler = HandlerStack::create($mock);
        $commentService = new CommentService(options: ['handler' => $handler]);

        $commentToAdd = new Comment('new comment', 'new text');

        $newComment = $commentService->addComment($commentToAdd);

        $this->assertIsInt($newComment->getId());
        $this->assertEquals($commentToAdd->getName(), $newComment->getName());
        $this->assertEquals($commentToAdd->getText(), $newComment->getText());
    }

    public function testUpdate(): void
    {
        $response = [
            'id' => 3,
            'name' => 'updated comment',
            'text' => 'updated text',
        ];
        $body = json_encode($response);

        $mock = new MockHandler([new Response(body: $body)]);
        $handler = HandlerStack::create($mock);
        $commentService = new CommentService(options: ['handler' => $handler]);

        $commentToUpdate = new Comment('updated comment', 'updated text', 3);

        $updatedComment = $commentService->updateComment($commentToUpdate);

        $this->assertEquals($commentToUpdate->getName(), $updatedComment->getName());
        $this->assertEquals($commentToUpdate->getText(), $updatedComment->getText());
    }
}