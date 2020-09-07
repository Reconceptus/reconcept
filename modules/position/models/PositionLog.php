<?php

namespace modules\position\models;

use common\helpers\FileHelper;
use common\helpers\StringHelper;
use common\helpers\Telegram;
use common\models\MActiveRecord;
use modules\config\models\Config;
use Yii;
use yii\httpclient\Client;

/**
 * This is the model class for table "position_log".
 *
 * @property string $id
 * @property int $request_id
 * @property string $query
 * @property string $domain
 * @property int $position
 * @property int $depth
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class PositionLog extends MActiveRecord
{
    public const STATUS_CONSOLE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'position_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_id', 'position', 'depth', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['query', 'domain'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'request_id' => 'Request ID',
            'query'      => 'Запрос',
            'domain'     => 'Домен',
            'position'   => 'Позиция',
            'depth'      => 'Глубина проверки',
            'status'     => 'Статус',
            'created_at' => 'Дата и время',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @param $q
     * @param $domain
     * @param  int  $depth
     * @param  int  $request_id
     * @return float|int
     */
    public static function getPosition($q, $domain, $depth = 100, $request_id = 0)
    {
        $result = 0;
        for ($page = 1; $page <= $depth / 100; $page++) {
            $res = self::parsePage($domain, simplexml_load_string(self::getYandexPage($q, $page)));
            if ($res) {
                $result = ($page - 1) * 100 + $res;
            }elseif ($res === null){
                break;
            }
        }
        $model = new self();
        $model->domain = $domain;
        $model->query = $q;
        $model->position = $result;
        $model->depth = $depth;
        $model->request_id = $request_id;
        if (Yii::$app->request->isConsoleRequest) {
            $model->status = self::STATUS_CONSOLE;
        }
        $model->save();
        return $result;
    }


    /**
     * @param $domain
     * @param $xml
     * @return int|null
     */
    public static function parsePage($domain, $xml)
    {
        $resp = $xml->response->results->grouping;
        $position = 0;
        if ($resp) {
            foreach ($resp->group as $k => $item) {
                $position++;
                $dom = (string) $item->doc->domain[0];
                if ($domain === $dom) {
                    return $position;
                } elseif (strpos($dom, $domain) !== false) {
                    Yii::$app->session->setFlash('Похожий на указанный в запросе домен '.$dom.' найден на позиции '.$position);
                }
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Ошибка при получении данных');
            Telegram::send($xml);
            return null;
        }
        return 0;
    }

    public static function getYandexPage($q, $page = 1)
    {
        $url = 'https://yandex.ru/search/xml';
        $client = new Client(['responseConfig' => ['format' => Client::FORMAT_XML],]);
        $response = $client->createRequest()
            ->setMethod('get')
            ->setUrl($url)
            ->setData([
                'user'    => Config::getValue('position_yandex_user'),
                'key'     => Config::getValue('position_yandex_key'),
                'l10n'    => 'ru',
                'sortby'  => 'rlv',
                'query'   => $q,
                'page'    => $page,
                'groupby' => 'mode=flat.groups-on-page=100.docs-in-group=1'
            ])
            ->send();
        if ($response->isOk) {
            $fileName = date('YmdHis', time()).'_'.mb_substr(StringHelper::translitString($q), 0, 100).'.xml';
            $path = Yii::getAlias('@frontend/web/uploads/yandex/xml/');
            FileHelper::createDirectory($path);
            file_put_contents($path.$fileName, $response->content);
            return $response->content;
        }
        Telegram::send($response->content);
    }
}
