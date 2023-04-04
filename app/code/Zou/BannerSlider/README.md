# M2插件-bannerslider  ( 支持2.3.x - 2.4.x )

#### 介绍
因为Magestore\Bannerslider插件官方停止更新维护了,在2.3.5/2.4.x版本里有很多兼容性的bug,为了避免这种问题，就创建了这个仓库，有问题我会及时修复。


#### 安装教程

1.  插件名 Zou_BannerSlider
2.  在项目根目录创建目录 `mkdir -p app/code/Zou/BannerSlider`
3.  下载插件源码，拷贝到`app/code/Zou/BannerSlider/`下面
4.  启用插件 `php bin/magento module:enable --clear-static-content Zou_BannerSlider`
5.  更新系统 `php bin/magento setup:upgrade && php bin/magento setup:di:compile && php bin/magento setup:static-content:deploy -f`


#### 注意事项

##### 1，记得把源代码里的`Magestore`替换成`Zou`

比如
`echo $this->getLayout()->createBlock("Magestore\Bannerslider\Block\SliderItem")->setSliderId(1)->toHtml();`

改成
`echo $block->getLayout()->createBlock('Zou\BannerSlider\Block\Widget')->setSliderId(1)->toHtml();`