<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 05.07.2018
 * Time: 11:11
 */

namespace floor12\metamaster;

use \Yii;
use yii\base\Component;
use yii\web\View;

/**
 * Class MetaMaster
 * @package app\components
 * @property string $siteName
 * @property string $type
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $url
 * @property string $defaultImage
 * @property string $image
 * @property string $imagePath
 * @property string $web
 * @property View $_view
 */
class MetaMaster extends Component
{
    public $siteName = 'My Test Application';
    public $type = 'article';
    public $title;
    public $keywords;
    public $description;
    public $url;
    public $defaultImage;
    public $image;
    public $imagePath;
    public $web = "@app/web";

    private $_view;

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }

    public function setImage($image, $imagePath = '')
    {
        $this->image = $image;
        $this->imagePath = $imagePath;
        return $this;
    }

    public function register(View $view)
    {
        $this->_view = $view;
        $this->registerCoreInfo();
        $this->registerTitle();
        $this->registerDescription();
        $this->registerKeywords();
        $this->registerImage();
    }

    private function registerCoreInfo()
    {
        $this->_view->registerMetaTag(['property' => 'og:site_name', 'content' => $this->siteName]);
        $this->_view->registerMetaTag(['property' => 'og:type', 'content' => $this->type]);
        $this->_view->registerMetaTag(['property' => 'og:url', 'content' => $this->url ?: Yii::$app->request->absoluteUrl]);
        $this->_view->registerMetaTag(['name' => 'twitter:card', 'content' => 'summary']);
        $this->_view->registerMetaTag(['name' => 'twitter:domain', 'content' => Yii::$app->request->hostInfo]);
        $this->_view->registerMetaTag(['name' => 'twitter:site', 'content' => $this->siteName]);
    }

    private function registerTitle()
    {
        if ($this->title) {
            $this->_view->title = $this->title;
            $this->_view->registerMetaTag(['property' => 'og:title', 'content' => $this->title]);
            $this->_view->registerMetaTag(['itemprop' => 'name', 'content' => $this->title]);
        }
    }

    private function registerKeywords()
    {
        if ($this->keywords) {
            $this->_view->registerMetaTag(['name' => 'keywords', 'content' => $this->keywords]);
        }
    }

    private function registerDescription()
    {
        if ($this->description) {
            $this->_view->registerMetaTag(['name' => 'description', 'content' => $this->description]);
            $this->_view->registerMetaTag(['property' => 'og:description', 'content' => $this->description]);
            $this->_view->registerMetaTag(['name' => 'twitter:description', 'content' => $this->description]);

        }
    }

    private function registerImage()
    {
        $image = $this->image ?: $this->defaultImage;
        if ($image) {

            $this->_view->registerMetaTag(['property' => 'og:image', 'content' => Yii::$app->request->hostInfo . $image]);
            $this->_view->registerMetaTag(['property' => 'twitter:image:src', 'content' => Yii::$app->request->hostInfo . $image]);
            $this->_view->registerMetaTag(['itemprop' => 'image', 'content' => Yii::$app->request->hostInfo . $image]);

        }
        $path = Yii::getAlias($this->imagePath ?: $this->web . $image);
        if (file_exists($path)) {
            $imageSize = getimagesize($path);
            $this->_view->registerMetaTag(['property' => 'og:image:width', 'content' => $imageSize[0]], 'og:image:width');
            $this->_view->registerMetaTag(['property' => 'og:image:height', 'content' => $imageSize[1]], 'og:image:height');
        }
    }
}