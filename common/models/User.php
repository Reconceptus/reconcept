<?php

namespace common\models;

use frontend\models\Profile;
use modules\shop\models\Favorite;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $role
 * @property string $last_name
 * @property string $first_name
 * @property string $patronymic
 * @property string $fio
 * @property string $image
 * @property string $phone
 * @property string $country
 * @property string $city
 * @property string $address
 * @property string $organization
 * @property string $position
 * @property string $auth_key
 * @property string $access_token
 * @property integer $type
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property Profile $profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const SEX_MAN = 0;
    const SEX_WOMAN = 1;
    const SEX_LIST = [
        self::SEX_MAN   => 'Муж',
        self::SEX_WOMAN => 'Жен'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!$this->fio) {
            $this->fio = mb_substr($this->last_name . ' ' . $this->first_name . ' ' . $this->patronymic, 0, 255);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$this->profile) {
            $profile = new Profile(['user_id' => $this->id]);
            $profile->save();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'fio', 'address'], 'string', 'max' => 255],
            [['last_name', 'first_name', 'patronymic', 'phone', 'country', 'city', 'organization', 'position'], 'string', 'max' => 85],
            [['role'], 'string', 'max' => 30],
            [['image'], 'file', 'extensions' => 'png, jpg, gif'],
            [['type'], 'integer'],
            [['username', 'email', 'status'], 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                   => 'ID',
            'username'             => 'Логин',
            'email'                => 'Email',
            'password_hash'        => 'Хэш пароля',
            'password_reset_token' => 'Токен сброса пароля',
            'role'                 => 'Роль',
            'status'               => 'Статус',
            'fio'                  => 'ФИО',
            'address'              => 'Адрес',
            'last_name'            => 'Фамилия',
            'first_name'           => 'Имя',
            'patronymic'           => 'Отчество',
            'phone'                => 'Телефон',
            'country'              => 'Страна',
            'city'                 => 'Город',
            'organization'         => 'Организация',
            'position'             => 'Должность',
            'created_at'           => 'Добавлен',
            'updated_at'           => 'Изменен'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    public static function getStatuses()
    {
        return [
            self::STATUS_DELETED => 'Отключен',
            self::STATUS_ACTIVE  => 'Активен'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @param $user
     * @param null $password
     */
    public static function sendRegLetter($user, $password = null)
    {
        Yii::$app->mailer->compose('registration', ['model' => $user, 'password' => $password])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Вы зарегистрированы на сайте ' . Yii::$app->name)
            ->send();
    }

    /**
     * Получаем список всех ролей (кроме гостя)
     * @return array
     */
    public static function getAccessTypes()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $result = [];
        foreach ($roles as $name => $role) {
            if ($name === 'guest')
                continue;
            $result[$name] = $role->description;
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function getAuthors()
    {
        $authors = User::find()->alias('u')->select(['u.fio', 'u.id'])
            ->where(['in', 'u.role', ['admin', 'manager']])->indexBy('id')->column();
        return $authors;
    }

    /**
     * @return User|null|IdentityInterface
     */
    public static function getUser()
    {
        return Yii::$app->user->identity;
    }

    /**
     * @return array
     */
    public static function getUserRoleList()
    {
        $roleList = User::find()->select('role')->distinct()->all();
        return ArrayHelper::map($roleList, 'role', 'role');
    }

    /**
     * @return ActiveQuery|array
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::className(), ['user_id' => 'id'])->all();
    }

    /**
     * @return array
     */
    public static function getFavoriteIds()
    {
        if (Yii::$app->user->isGuest) {
            return [];
        }
        return ArrayHelper::map(User::getUser()->getFavorites(), 'item_id', 'id');
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        if ($this->image) {
            return $this->image;
        }
        return Yii::$app->params['defaultAvatar'];
    }
}
