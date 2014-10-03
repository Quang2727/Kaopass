<?php
echo $this -> Html -> meta(array(
    'name' => 'viewport' ,
    'content' => 'width=device-width, initial-scale=1.0'
));
echo $this->Html->meta(array(
    'rel' => 'apple-touch-icon',
    'link' => Router::url('/img/ios/apple-touch-icon.png')
));
echo $this->Html->meta(array(
    'rel' => 'apple-touch-icon-precomposed',
    'link' => Router::url('/img/ios/apple-touch-icon-precomposed.png')
));
echo $this->Html->meta(array(
    'rel' => 'apple-touch-icon-precomposed',
    'sizes' => '57x57',
    'link' => Router::url('/img/ios/apple-touch-icon-57x57-precomposed.png')
));
echo $this->Html->meta(array(
    'rel' => 'apple-touch-icon-precomposed',
    'sizes' => '72x72',
    'link' => Router::url('/img/ios/apple-touch-icon-72x72-precomposed.png')
));
echo $this->Html->meta(array(
    'rel' => 'apple-touch-icon-precomposed',
    'sizes' => '114x114',
    'link' => Router::url('/img/ios/apple-touch-icon-114x114-precomposed.png')
));
echo $this->Html->meta(array(
    'rel' => 'apple-touch-icon-precomposed',
    'sizes' => '144x144',
    'link' => Router::url('/img/ios/apple-touch-icon-144x144-precomposed.png')
));