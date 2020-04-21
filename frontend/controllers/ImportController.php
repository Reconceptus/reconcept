<?php

namespace frontend\controllers;

use common\helpers\TranslitHelper;
use common\models\User;
use modules\blog\models\Comment;
use modules\blog\models\Post;
use modules\blog\models\PostTag;
use modules\blog\models\Tag;
use modules\portfolio\models\Portfolio;
use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\Controller;

/**
 * Import controller
 */
class ImportController extends Controller
{

    public function actionPost()
    {
        return null;
        $json = file_get_contents('select_posts.json');
        $file = json_decode($json, true)['RECORDS'];
        foreach ($file as $data) {
            if ($data['content'] && $data['introtext'] !== null) {
                if (!Post::find()->where(['slug' => $data['uri']])->exists()) {
                    $post = new Post();
                    $post->author_id = 1;
                    $post->slug = $data['uri'];
                    $post->name = $data['longtitle'];
                    $post->intro = $data['introtext'];
                    $post->text = $data['content'];
                    $post->views = $data['views'];
                    $post->status = $data['published'] ? Post::STATUS_ACTIVE : Post::STATUS_DISABLED;
                    $post->save();
                }
            }
        }
    }

    public function actionPostseo()
    {
        return null;
        $json = file_get_contents('select_posts.json');
        $file = json_decode($json, true)['RECORDS'];
        foreach ($file as $data) {
            $post = Post::findOne(['slug' => $data['uri']]);
            if ($post) {
                $post->name = $data['pagetitle'];
                $post->title = $data['longtitle'];
                $post->description = $data['description'];
                $post->save();
            }
        }
    }

    public function actionPostdate()
    {
        return null;
        $json = file_get_contents('select_posts.json');
        $file = json_decode($json, true)['RECORDS'];
        foreach ($file as $data) {
            if ($data['content'] && $data['introtext'] !== null) {
                $post = Post::find()->where(['slug' => $data['uri']])->one();
                if ($post) {
                    $post->created_at = date('Y-m-d H:i:s', intval($data['createdon']));
                    $post->save();
                }
            }
        }
    }

    public function actionPortfolio()
    {
        return null;
        $json = file_get_contents('modx_site_content.json');
        $file = json_decode($json, true)['RECORDS'];
        foreach ($file as $data) {
            if (!Portfolio::find()->where(['slug' => $data['uri']])->exists()) {
                $post = new Portfolio();
                $post->author_id = 1;
                $post->slug = $data['uri'];
                $post->name = $data['pagetitle'];
                $post->full_name = $data['longtitle'];
                $post->content = $data['description'];
                $post->status = $data['published'] ? Portfolio::STATUS_ACTIVE : Portfolio::STATUS_DISABLED;
                if (!$post->save()) {
                    var_dump($post->errors);
                    die;
                }
            }
        }
        var_dump($file);
    }

    public function actionComments()
    {
        return null;
        $json = file_get_contents('comments.json');
        $file = json_decode($json, true)['RECORDS'];
        foreach ($file as $data) {
            if (!Comment::find()->where(['old_id' => $data['id']])->exists()) {
                if ($data['parent'] > 0) {
                    $root = Comment::find()->alias('c')->joinWith('post p')->where(['p.slug' => $data['uri'], 'c.old_id' => $data['parent']])->one();
                } else {
                    $root = Comment::find()->alias('c')->joinWith('post p')->where(['p.slug' => $data['uri'], 'c.depth' => 0])->one();
                }
                if ($root) {
                    $user_id = null;
                    $user = User::findOne(['email' => $data['email']]);
                    if ($user) {
                        $user_id = $user->id;
                    }
                    $comment = Comment::add($root, $data['name'], $data['text'], $data['email'], $user_id);
                    if (!$comment) {
                        var_dump($data);
                        die;
                    }
                    $comment->ip = $data['ip'];
                    $comment->old_id = $data['id'];
                    if (!$comment->save()) {
                        var_dump($comment->errors);
                        die;
                    }
                } else {
                    var_dump($data);
                }
            }
        }
    }

    public function actionCommentsdate()
    {
        return null;
        $json = file_get_contents('comments.json');
        $file = json_decode($json, true)['RECORDS'];
        foreach ($file as $data) {
            $comment = Comment::find()->where(['old_id' => $data['id']])->one();
            if ($comment) {
                $time = strtotime(str_replace('.', '-', $data['createdon']));
                $comment->created_at = date('Y-m-d H:i:s', $time);
                $comment->save();
            }
        }
    }

    public function actionTags()
    {
        return null;
        $json = file_get_contents('tags.json');
        $file = json_decode($json, true)['RECORDS'];
        foreach ($file as $data) {
            if ($data['uri']) {
                $tag = Tag::findOne(['name' => $data['value']]);
                if (!$tag) {
                    $tag = new Tag(['name' => $data['value']]);
                    if (!$tag->save()) {
                        var_dump($tag);
                        var_dump($tag->errors);
                        die;
                    }
                }
                $post = Post::findOne(['slug' => $data['uri']]);
                $isExistPostTag = PostTag::find()->where(['post_id' => $post->id, 'tag_id' => $tag->id])->exists();
                if (!$isExistPostTag) {
                    $postTag = new PostTag(['post_id' => $post->id, 'tag_id' => $tag->id]);
                    if (!$postTag->save()) {
                        var_dump($postTag->errors);
                        die;
                    }
                }
            }
        }
        var_dump($file);
    }

    public function actionImages()
    {
        return null;
        $url = 'https://journal.reconcept.ru';
        $this->parsePage($url, $url);
        for ($i = 2; $i < 21; $i++) {
            $url2 = $url . '/?page=' . $i;
            $this->parsePage($url, $url2);
        }
    }

    public function parsePage($url, $url2)
    {
        return null;
        $client = new \yii\httpclient\Client();
        $request = $client->createRequest()
            ->setMethod('get')
            ->setUrl($url2)
            ->addHeaders(['user-agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'])
            ->send();
        $page = \phpQuery::newDocumentHTML($request->content);
        foreach (pq('#mse2_results > .blog_item') as $post) {
            $href = pq($post)->find('a')->attr('href');
            $article = Post::findOne(['slug' => $href]);
            if ($article) {
                if (!$article->image_preview) {
                    $src = pq($post)->find('.imgBox > img')->attr('src');
                    $srcArr = explode('.', $src);
                    $ext = end($srcArr);
                    $file = file_get_contents($url . $src);
                    $dir = \Yii::getAlias('@frontend/web');
                    $path = '/uploads/images/Old/' . date('ymdHis') . '/';
                    FileHelper::createDirectory($dir . $path);
                    $fileName = time() . '_' . \Yii::$app->security->generateRandomString(2) . '.' . $ext;
                    file_put_contents($dir . $path . $fileName, $file);
                    $article->image_preview = $path . $fileName;
                    $article->save();
                } else {
                    echo 2;
                }
            } else {
                echo 1;
            }
        }
    }

    public function actionParse()
    {
        return null;
        $posts = Post::find()->where(['is', 'parsed', null])->all();
        foreach ($posts as $post) {
            $post->text = self::parseContent($post);
            $post->parsed = 1;
            if (!$post->save()) {
                var_dump($post->errors);
                die;
            }
        }
    }

    public static function parseContent($post)
    {
        return null;
        $content = $post->text;
        if ($content) {
            $pattern = '/src[^>]+(?:jpg|jpeg|png)" /i';
            $result = preg_replace_callback($pattern,
                function ($matches) use ($post) {
                    $src = trim(str_replace('src=', '', $matches[0]), '" ');
                    $hasProtocol = strpos($src, 'beget.com');
                    if ($hasProtocol) {
                        return $matches[0];
                    }
                    $url = 'https://journal.reconcept.ru';
                    $srcArr = explode('.', $src);
                    $ext = end($srcArr);
                    $file = file_get_contents($url . '/' . $src);
                    $dir = \Yii::getAlias('@frontend/web');
                    $path = '/uploads/images/Oldpost/' . date('ymdHis') . '/';
                    FileHelper::createDirectory($dir . $path);
                    $fileName = time() . '_' . \Yii::$app->security->generateRandomString(2) . '.' . $ext;
                    file_put_contents($dir . $path . $fileName, $file);
                    $resultString = 'src="' . $path . $fileName . '" ';
                    if (!$post->image) {
                        $post->image = $path . $fileName;
                        $post->save();
                        $resultString = '';
                    }
                    return $resultString;
                },
                $content);
            return $result;
        }
        return null;
    }

    public function actionDeleteempty()
    {
        return null;
        $posts = Post::find()->all();
        foreach ($posts as $post) {
            $post->text = self::deleteEmpty($post);
            if (!$post->save()) {
                var_dump($post->errors);
                die;
            }
        }
    }

    public static function deleteEmpty($post)
    {
        return null;
        $content = $post->text;
        if ($content) {
            $pattern = '/<img[^>]+>/i';
            $result = preg_replace_callback($pattern,
                function ($matches) use ($post) {
                    $src = $matches[0];
                    $isSrc = mb_strpos($src, 'src=');
                    if (!$isSrc) {
                        return '';
                    }
                    return $src;
                },
                $content);
            return $result;
        }
        return null;
    }

    public function actionTag()
    {
        $models = Tag::find()->all();
        foreach ($models as $model) {
            if (!$model->slug) {
                $model->slug = TranslitHelper::encodestring($model->name);
                if (!$model->save()) {
                    throw new Exception($model->getErrorSummary(false)[0]);
                }
            }
        }
    }

    public function actionTelegram()
    {
        $data = Yii::$app->telegram->getMe();
        var_dump($data);die;
    }
}
