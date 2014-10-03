<!DOCTYPE html>
    <!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
    <!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
    <!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
    <!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <?php
        echo $this -> Html -> charset('utf-8');
        echo $this -> Html -> meta(array('http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge,chrome=1'));
        echo $this -> Html -> meta('icon', Router::url('/favicon.png'), array('type'=>'image/png'));
        echo $this -> Html -> meta('keywords', array('error'));
        echo $this -> Html -> meta('description' , array('Error page'));
        echo $this->Element('apple_metas');
        echo $this->fetch('meta');

        echo $this -> Html -> css(array('bootstrap.min', 'frontend/style', 'frontend/theme'));
        //echo $this -> Html -> css(array()); /* for theming */
        //echo $this -> Html -> css(array()); /* for plugins */
        echo $this -> fetch('css');
        ?>

        <title><?php echo $title_for_layout; ?></title>

        <style type="text/css">
            .container {
                max-width: 730px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <!-- Main jumbotron for a primary marketing message or call to action -->
            <div class="hero-unit">
                <div class="container">
                    <h1>404</h1>

                    <p>
                        Không thể xử lý yêu cầu của bạn.
                    </p>

                    <p>
                        <a class="btn btn-default" href="<?php echo Router::url('/')?>">Quay về trang chủ</a>
                    </p>
                </div>
            </div>

            <div class="container">
                <!-- Example row of columns -->
                <div class="row">
                    <?php echo $this->Session->flash(); ?>

                    <?php echo $this -> fetch('content'); ?>
                </div>
            </div>
            <!-- /container -->
        </div>

        <?php echo $this->Element('footer-links'); ?>

        <div id="footer">
            <span class="pull-right">
                <?php
                    echo $this->Html->image('footer_logo.gif');
                ?>
            </span>
            <span>
                &copy; QA Site <?php echo date('Y');?>
            </span>
        </div><!-- /#footer -->
    </body>
</html>
