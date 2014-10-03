<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('body_id'); ?>pageID_about<?php $this->end();?>

<?php $this->Html->css(array('login/input'), null, array('inline' => false)); ?>
<?php $this->Html->css(array('about/about'), null, array('inline' => false)); ?>

    <section class="bkgAboutTitle">
        <div class="boxAboutTitle clearfix">
            <div class="boxAboutTitleInner clearfix">
                <h1 class="ttlAbout"><img src="/img/about/imgAboutTitle.png" width="594" height="90" alt="開発中の「困った」はエンジニア同士で解決へ　teratailは、思考するエンジニアのための課題解決プラットフォームです。"/></h1>
<?php if (!isset($User)) { ?>
                <p><a class="btnJoin" href="/login/input">Tham gia teratail</a></p>
<?php } ?>
            </div>
        </div>
    </section>

    <section class="sectionAbout sectionAbout_01">
        <div class="sectionInner clearfix">
            <h2 class="ttlSection">Hỗ trợ bằng markdown<br>dễ dàng đặt câu hỏi, dễ dàng trả lời.</h2>
            <div class="sectionInnerLeft floatL">
                <p class="txtSection">"Hỏi những vấn đề mà bạn mãi không thể tự giải đáp được"<br>"Người biết, chỉ dẫn cho người chưa biết"<br>teratail đã ra đời chỉ để thực hiện những điều "hiển nhiên" như vậy.</p>
                <p class="txtSection"> Soạn câu hỏi bằng công cụ markdown quen thuộc, code được hiển thị nổi (hightlight) theo đúng cú pháp code, và như vậy nội dung hỏi của bạn sẽ được soạn theo một các trực quan và dễ hiểu nhất.</p>
                <p class="txtSection">Các thành viên của teratail còn có thể sử dụng chức năng hệ thống hỗ trợ để đăng câu hỏi của mình lên mạng xã hội, trên mạng xã hội câu hỏi của bạn sẽ tiếp cận được đến nhiều người hơn, và biết đâu có những người đã trải qua vấn đề tương tự như bạn trong quá khứ, họ sẽ là người có khả năng đưa ra đáp án phù hợp nhất cho bạn thì sao.</p>
            </div>
            <div class="sectionInnerRight floatL">
                <p><img src="/img/about/imgSection01.png" width="450" height="300" alt="Hỗ trợ bằng markdown, dễ dàng đặt câu hỏi, dễ dàng trả lời." /></p>
            </div>
        </div>
    </section>

    <section class="sectionAbout sectionAbout_02">
        <div class="sectionInner clearfix">
            <h2 class="ttlSection">ERROR trong một tương lai rất gần<br> sẽ có thể hoàn toàn biến mất ???</h2>
            <div class="sectionInnerLeft floatL">
                <p class="txtSection">"Tìm hiểu nguyên nhân của lỗi thì cũng mất đến 30 phút..."<br> Để giảm bớt tình trạng này, ở Nhật chúng tôi thường xuyên có một danh sách “TRY - ERROR - FIXED”. Các danh sách này được xem như là "kiến thức" của những kỹ sư phần mềm, và teratail mong muốn "kiến thức" này được chia sẻ với nhau để nâng cao tính hiệu quả trong công việc làm phần mêm, đây là một suy nghĩ thực sự nghiêm túc của chúng tôi. </p>
                <p class="txtSection">「MyTag」là một trong những chức năng cho mục đích đó<br>Việc đăng ký Tag cho những công cụ hay ngôn ngữ lập trình mà bản thân bạn thường dùng, sẽ giúp hệ thống cập nhật những thông tin phù hợp với Tag tại trang MyPage của bạn.</p>
                <p class="txtSection">Hãy sử dụng teratail vì bạn không chỉ sẽ có thể giải quyết vấn đề đang đối mặt một cách nhanh chóng và triệt để, mà còn để năm bắt thông tin biết đâu nó có thể giúp bạn tránh khỏi những lỗi của ngày mai sẽ mắc phải? và đương nhiên mục đích cuối cùng là nâng cao hiệu quả công việc</p>
            </div>
            <div class="sectionInnerRight floatL">
                <p><img src="/img/about/imgSection02.png" width="450" height="430" alt="Trong tương lai gần hoàn toàn có thể tránh được các ERROR" /></p>
            </div>
        </div>
        <div class="sectionInner clearfix">
            <h3 class="ttlFuture">Phát triển thành một platform đáp ứng cho nhiều nhu cầu hơn.</h3>
            <div class="sectionInnerLeft sectionFuture01 floatL">
                <div class="boxFuture">
                    <h4 class="ttlSubFuture">Nâng cao khả năng tìm kiếm</h4>
                    <p class="txtFuture">Teratail sẽ mỗi ngày một phát triển hơn<br>Việc đó có thể đạt được rất đơn giản bằng chất lượng trong những câu hỏi của mọi người <br>Chúng tôi cũng sẽ nâng cao khả năng tìm kiếm của hệ thống để có thể cung cấp thông tin giàu giá trị dựa trên "từ khóa và thông tin về người sử dụng"</p>
                </div>
            </div>
            <div class="sectionInnerRight sectionFuture02 floatL">
                <div class="boxFuture">
                    <h4 class="ttlSubFuture">Nâng cao khả năng kết nối mọi người</h4>
                    <p class="txtFuture">Chúng tôi đang cố gắng tạo nên một nơi để bạn có thể gặp được những lập trình viên nổi tiếng cũng như xuất sắc. Hiện tại chúng tôi cũng đang lên cân nhắc về các chức năng tạo cộng đồng (community) nâng cao năng lực kỹ thuật, cũng như chức năng đặt câu hỏi cho một thành viên nổi tiếng nào đó.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sectionAbout sectionAbout_03">
        <div class="sectionInner clearfix">
            <h2 class="ttlSection">Những lập trình viên xuất sắc <br>có thể phát huy thực lực tại Teratail</h2>
            <div class="sectionInnerLeft floatL">
                <p class="txtSection">
                Với cơ cấu đánh giá các thành viên qua các hoạt động, đặt câu hỏi, và giải đáp, chúng tôi tạo nên một hệ thống huy hiệu và điểm số cho từng cá nhân riêng. Dựa vào đó các thành viên cũng có thể đánh giá lẫn nhau qua số điểm, chúng tôi thực sự nghĩ bạn hoàn toàn có thể tin cậy ở hệ thống đánh giá này.</p>
                <p class="txtSection">Chúng tôi nghĩ cho dù không phải là CTO, cho dù không đứng trên bục giảng để diễn thuyết, hay chậm chí là không có cả khiếu nói chuyện trước đám đông, chỉ cần xem source code sẽ có rất nhiều lập trình viên như bạn sẽ cho chúng tôi thấy bạn tuyệt vời đến như thế nào.<br>Chúng tôi mong bạn đừng để tài năng đó bị chôn vùi như vậy, mà hãy phát huy tại teratail.</p>
            </div>
            <div class="sectionInnerRight floatL">
                <p><img src="/img/about/imgSection03.png" width="450" height="232" alt="Nơi mà các lập trình viên xuất sắc có thể phát huy được thực lực của bản thân" /></p>
            </div>
        </div>
    </section>

<?php if (!isset($User)) { ?>
    <section class="boxJoin">
        <div id="content" class="clearfix">
            <h2 class="ttlJoin">Nào, hãy cùng tham gia vào teratail thôi!</h2>
            <div class="floatL boxSNSJoin">
                <p class="btnLogin"> <a href="/login/social/facebook"><img src="/img/login/btnLoginFacebook.png" alt="Đăng nhập bằng Facebook" width="260" height="41"></a> </p>
                <p class="btnLogin"> <a href="/login/social/twitter"><img src="/img/login/btnLoginTwitter.png" alt="Đăng nhập bằng Twitter" width="260" height="41"></a> </p>
                <p class="btnLogin"> <a href="/login/social/google"><img src="/img/login/btnLoginGoogle.png" alt="Đăng nhập bằng Google" width="260" height="41"></a> </p>
                <p class="btnLogin"> <a href="/login/social/github"><img src="/img/login/btnLoginGithub.png" alt="Đăng nhập bằng Github" width="260" height="41"></a> </p>
                <!-- <p class="btnLoginLast"> <a href="/login/social/hatena"><img src="/img/login/btnLoginHatena.png" alt="Đăng nhập bằng Hatena" width="260" height="41"></a> </p> -->
            </div>
            <form action="/login/input" class="form-horizontal margin-none" id="UserSignupForm" method="post" accept-charset="utf-8">
                <div id="boxForm" class="js-validation">
                    <p class="ttlSub ttlSub_bkgBk">Đăng ký tài khoản mới</p>
                    <ul>
                        <li class="boxLabelInput">
                            <p class="ttlInput">Tên thành viên  (3 ~ 15 ký tự)</p>
                            <div>
                                <input name="data[TmpUser][display_name]" id="name" class="mod-inputField mod-inputField-max" maxlength="15" type="text" required>
                            </div>
                        </li>
                        <li class="boxLabelInput">
                            <p class="ttlInput">Địa chỉ mail</p>
                            <div>
                                <input name="data[TmpUser][mail_address]" id="mail_address" class="mod-inputField mod-inputField-max" type="text" required>
                            </div>
                        </li>
                        <li class="boxLabelInput">
                            <p class="ttlInput">Mật khẩu  (6~ 20 ký tự và không sử dụng ký hiệu)</p>
                            <div>
                                <input name="data[TmpUser][password]" id="password" class="mod-inputField mod-inputField-max" type="password" required>
                            </div>
                        </li>
                        <li class="boxLabelInput">
                            <p class="ttlInput">Mật khẩu (xác nhận)</p>
                            <div>
                                <input name="data[TmpUser][repeat_password]" id="password_check" class="mod-inputField mod-inputField-max" type="password" required>
                            </div>
                        </li>
                    </ul>
                    <p class="txtInput"> Sau khi bạn đã xác nhận những điều khoản liên quan đến <a href="/legal" target="_blank">Điều khoản sử dụng</a>, và <a href="/privacy" target="_blank">Việc sử dụng thông tin cá nhân</a> hãy nhấn nút "Đồng ý đăng ký" nếu bạn đồng ý. </p>
                    <div>
                        <button type="submit" id="save" class="mod-btn mod-btnRegister mod-icn l-btnSignup-center" value="同意して登録する" disabled>Đồng ý đăng ký</button>
                    </div>
                </div>
            </form>
            <div class="clearfix boxPrivacy">
                <p class="floatL"><a href="http://privacymark.jp/" rel="nofollow" target="_blank"><img src="/img/common/imgPrivacy.gif" alt="Privacy mark"></a></p>
                <p class="txtInput">Xin vui lòng thiết lập nhận mail có tên miền là "@leverages.jp"<br>Trường hợp chưa cài đặt, những mail quan trọng của hệ thống teratail cũng như những liên lạc từ phía công ty chúng tôi có thể sẽ không gửi đến được địa chỉ mail của bạn</p>
            </div>
        </div>
    </section>
<?php  } ?>
    <section class="boxSnsArea clearfix">
        <div class="btnWrap floatL">
            <a href="https://twitter.com/share" class="twitter-share-button" data-text="teratail -テラテイル- Trang QA chia sẻ suy nghĩ của các lập trình viên" data-lang="ja" data-hashtags="teratail">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        </div>
        <div class="btnWrap floatL">
            <div class="fb-like" data-href="https://vn.teratail.com/about" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.0";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        </div>
        <div class="btnWrap floatL">
            <div class="g-plusone" data-href="https://vn.teratail.com/about"></div>
            <script type="text/javascript">
             window.___gcfg = {lang: 'ja'};

             (function() {
               var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
               po.src = 'https://apis.google.com/js/platform.js';
               var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
             })();
            </script>
        </div>
    </section>
