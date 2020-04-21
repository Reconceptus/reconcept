<?php

use modules\blog\models\Comment;

/**
 * Created by PhpStorm.
 * User: adm
 * Date: 19.12.2018
 * Time: 15:04
 */
/* @var $comment Comment */
?>
<?php if ($comment->status !== Comment::STATUS_WAIT): ?>
    <div class="comment" id="comment-<?= $comment->id ?>" data-id="<?= $comment->id ?>">
        <?php if ($comment->status === Comment::STATUS_PUBLISHED): ?>
            <div class="comment-ava">
                <?php if ($comment->author_id): ?>
                    <div class="letter"><?= mb_substr($comment->name, 0, 1) ?></div>
                <?php else: ?>
                    <div class="letter"><?= mb_substr($comment->name, 0, 1) ?></div>
                <?php endif; ?>
            </div>
            <div class="comment-main-data">
                <div class="comment-top-data">
                    <div class="comment-name"><?= $comment->name ?></div>
                    <div class="comment-date"><?= \common\helpers\DateTimeHelper::getDateTimeRuFormat($comment->created_at) ?></div>
                </div>
                <div class="comment-text"><?= $comment->text ?></div>
                <div class="comment-actions">
                    <button type="button" class="button">Ответить</button>
                </div>
            </div>
        <?php elseif ($comment->status === Comment::STATUS_DELETED): ?>
            <div class="comment-deleted">Комментарий удален</div>
        <?php endif; ?>
    </div>
<?php endif; ?>