<?php
namespace Kourtis\Repositories;

use Kourtis\Models\Post;

class StaticBlogRepo
{
    
    public function __construct()
    {
    }
    
    public function getAllPosts()
    {
        $rawPosts = [
            [
                'title' => 'A subject that is beautiful in itself',
                'summary' => 'And this is a nice summary of what I am going to describe in the full article for you
                                guys. Don\'t worry about it, just dive in.',
                'photo' => 'img/test/asd5.jpg',
            ],

            [
                'title' => 'Is art everything to anybody?',
                'summary' => 'And this is a nice summary of what I am going to describe in the full article for you
                                guys. Don\'t worry about it, just dive in.',
                'photo' => 'img/test/asd2.jpg',
            ],

            [
                'title' => 'A great artist is always before his time',
                'summary' => 'And this is a nice summary of what I am going to describe in the full article for you
                                guys. Don\'t worry about it, just dive in.',
                'photo' => 'img/test/asd3.png',
            ],

            [
                'title' => 'A subject that is beautiful in itself',
                'summary' => 'And this is a nice summary of what I am going to describe in the full article for you
                                guys. Don\'t worry about it, just dive in.',
                'photo' => 'img/test/asd4.png',
            ],

            [
                'title' => 'Is art everything to anybody?',
                'summary' => 'And this is a nice summary of what I am going to describe in the full article for you
                                guys. Don\'t worry about it, just dive in.',
                'photo' => 'img/test/asd1.jpg',
            ],

            [
                'title' => 'A great artist is always before his time',
                'summary' => 'And this is a nice summary of what I am going to describe in the full article for you
                                guys. Don\'t worry about it, just dive in.',
                'photo' => 'img/test/asd6.png',
            ],

            [
                'title' => 'A subject that is beautiful in itself',
                'summary' => 'And this is a nice summary of what I am going to describe in the full article for you
                                guys. Don\'t worry about it, just dive in.',
                'photo' => 'img/test/asd7.jpg',
            ],

            [
                'title' => 'A great artist is always before his time',
                'summary' => 'And this is a nice summary of what I am going to describe in the full article for you
                                guys. Don\'t worry about it, just dive in.',
                'photo' => 'img/test/asd5.jpg',
            ],

            [
                'title' => 'A subject that is beautiful in itself',
                'summary' => 'And this is a nice summary of what I am going to describe in the full article for you
                                guys. Don\'t worry about it, just dive in.',
                'photo' => 'img/test/asd2.jpg',
            ],

            [
                'title' => 'Is art everything to anybody?',
                'summary' => 'And this is a nice summary of what I am going to describe in the full article for you
                                guys. Don\'t worry about it, just dive in.',
                'photo' => 'img/test/asd4.png',
            ],
        ];
        
        $posts = [];
        
        foreach ($rawPosts as $rawPost)
        {
            $posts[] = new Post($rawPost);
        }

        return $posts;
    }
}