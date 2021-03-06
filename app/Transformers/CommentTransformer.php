<?php

namespace App\Transformers;

use App\Models\Article;
use App\Models\Comment;

class CommentTransformer extends BaseTransformer
{
    protected $availableIncludes = ['user', 'target', 'replys'];

    public function transform(Comment $comment)
    {
        $data = $comment->toArray();

        return $data;
    }

    public function includeUser(Comment $comment)
    {
        return $this->item($comment->user, new UserTransformer, 'user');
    }

    public function includeTarget(Comment $comment)
    {
        if ($comment->target instanceof Article) {
            return $this->item($comment->target, new ArticleTransformer, 'target');
        }

        return $this->null();
    }

    public function includeReplys(Comment $comment)
    {
        return $this->collectionAndEagerLoadRelations($comment->replys, new ReplyTransformer, 'replys');
    }
}