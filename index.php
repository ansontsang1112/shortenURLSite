<!DOCTYPE html>
<html lang="en">

<?php
include_once "backend/pdo/fn.php";
include_once "backend/connection/redis.php";
include_once "backend/dao/UrlRepositoryImpl.php";
include "backend/model/User.php";
include "backend/model/Url.php";
session_start();

if ($_SESSION['isLogin']) {
  $isLogin = true;
} else {
  $isLogin = false;
}

$_SESSION['clientIP'] = getUserIP();

if ($isLogin) {
  // Actions after logged-in
  $impl = new UrlRepositoryImpl();
  $_SESSION['url_list'] = $impl->getUrlObjectByUser($_SESSION['uid']);
}
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Magic URL - ResonanceCraft Network (HN)</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link rel="shortcut icon" type="image/x-icon" href="https://cdn.hypernology.com/images/favicon.png" />
  <link href="https://cdn.hypernology.com/images/hn-icon.jpg" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center">
    <div class="container d-flex flex-column align-items-center">

      <h1>Magic URL 短網址</h1>
      <h2>免註冊，方便又實用。將你網址，縮短並儲存 QR Code 方便使用吧！</h2>

      <div class="subscribe">
        <h4>在下方輸入完整網址，讓我們把它「縮短」吧！</h4>
        <form action="backend/pdo/generate.php" method="post" role="form" class="php-email-form">
          <div class="subscribe-form">
            <input type="url" name="url" id='inputurl' placeholder="Shorten Your Link" required><input type="submit" value="生成">
          </div>
          <small>為確保不會出現任何濫用之情況，本網站將會記錄你的 IP 位置。現在你的 IP 位置：<strong><?php echo getUserIP(); ?></strong></small>
        </form>
        <?php
        $r = $GLOBALS['redis'];
        if (isset($_GET['s'])) {
          $code = $_GET['s'];
          $r = getKeyInfo($code);
        ?>
          <div class="mt-2">
            <div class="card" style="background-color: rgba(0,0,0,.4); color: #fff">
              <div class="card-body">
                <div class="container">
                  <div class="row">
                    <div class="col-5">
                      <?php echo $r['url']; ?>
                    </div>
                    <div class="col-5">
                      <a href="https://l.ttfmc.net/<?php echo $r['code']; ?>">https://l.ttfmc.net/<?php echo $r['code']; ?></a>
                    </div>
                    <div class="col-2">
                      <button type="button" class="btn btn-outline-info" onclick="copyText()">複製</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <script>
            let url = document.getElementById('inputurl').setAttribute("value", 'https://l.ttfmc.net/<?php echo $r['code']; ?>/');

            function copyText() {
                let copyText = document.getElementById("inputurl");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(copyText.value);
                outFunc();
            }

            function outFunc() {
                window.alert("已經成功複製到剪貼板當中");
            }
          </script>
        <?php } ?>
      </div>

      <div class="social-links text-center">
        <a href="https://dc.hypernite.com" class="discord"><i class="bi bi-discord"></i></a>
        <a href="https://www.facebook.com/hyperniteltd" class="facebook"><i class="bi bi-facebook"></i></a>
        <a href="https://www.instagram.com/hypergroup_mc" class="instagram"><i class="bi bi-instagram"></i></a>
        <a href="https://dc.resonancecraft.net" class="discord"><i class="bi bi-discord"></i></a>
      </div>

    </div>
  </header><!-- End #header -->

  <!-- ======= Login Section ======= -->
  <main id="main">
    <section id="contact" class="contact">
      <div class="container">

        <div class="section-title">
          <h2>帳號區域</h2>
          <?php
          if (isset($_GET['logout'])) {
          ?>
            <div class="alert alert-success" role="alert">
              感謝您使用本服務，你的帳號已經登出成功。
            </div>
          <?php } ?>
        </div>

        <div class="row">

          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="info">
              <?php if (!$_SESSION['isLogin']) { ?>
                  <?php
                  if (isset($_GET['login_err'])) {
                      if($_GET['login_err'] == "fail_login") {
                      ?>
                      <div class="alert alert-danger" role="alert">
                          帳號或密碼錯誤，請再嘗試。
                      </div>
                  <?php } else { ?>
                          <div class="alert alert-warning" role="alert">
                              你的帳號已經轉移並合併到 Discord 系統當中。請使用 Discord 登入。
                          </div>
                  <?php }
                  }?>
                <form action="backend/login/member.php?action=login" method="post">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username 用戶名</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Password 密碼</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>
                  <button type="submit" name="passcode_login" class="btn btn-primary">登入</button>
                </form>
                <hr />
                <div class="d-grid gap-2">
                  <button class="btn btn-info" type="button" onclick="window.location='backend/login/discord.php?action=login'">使用 Discord 帳號登入</button>
                </div>
              <?php } else { ?>
                <?php

                  if ($_SESSION['method'] == "discord") {
                      $user = $_SESSION['userObject'];
                ?>
                  <div class="alert alert-info" role="alert">
                    [ Magic URL ]<br>用戶平台 User Panel<button type="button" onclick="window.location='backend/login/discord.php?action=logout'" style="float: right;" class="btn-light">登出</button>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <span style="color:black">登入名稱：<?php echo $user->username . "#" . $user->discriminator; ?><br>電郵：<?php echo $user->email; ?></span>
                    </div>
                  </div>
                      <?php if(!$_SESSION['isLinkedMember']) { ?>
                      <hr>
                      <div class="card" style="background-color: rgba(255, 255, 255, 0)">
                          <button type="button" onclick="window.open('migrate.php','targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=900'); return false;" style="color: white" class="btn btn-outline-dark">連結會員帳號<br>Link Member Account</button>
                      </div>
                      <small>請注意：若果您曾經使用會員帳號登入，連結後將不能再使用會員帳號作登入。並且曾經建立的短網址亦會遷移到本帳號當中。</small>
                      <?php } ?>
                <?php } else {
                      $user = unserialize($_SESSION['userObject']);?>
                      <div class="alert alert-info" role="alert">
                          [ Magic URL ]<br>用戶平台 User Panel<button type="button" onclick="window.location='backend/login/member.php?action=logout'" style="float: right;" class="btn-light">登出</button>
                      </div>
                      <div class="card">
                          <div class="card-body">
                              <span style="color:black">登入名稱：<?php echo $user->getUsername(); ?><br>電郵：<?php echo $user->getEmail(); ?></span>
                          </div>
                      </div>
                  <?php } ?>
              <?php } ?>
            </div>
          </div>

          <div class="col-lg-8 mt-5 mt-lg-0 d-flex align-items-stretch">
            <?php
            if (!$isLogin) {
            ?>
              <div class="info" style="width: 100%;">
                <div class="card-body">
                  <h5 class="card-title">Learn more</h5>
                  <div class="accordion info">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#whatisshorten" aria-expanded="false" aria-controls="whatisshorten">
                          什麼是短網址？
                        </button>
                      </h2>
                      <div id="whatisshorten" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <span style="color:black">
                            短網址是將原有很長的網址縮短，並運用轉址技術，重定向你的網址。<br><code>https://www.resonancecraft.net/apply.php -> https://l.ttfmc.net/852jdk</code>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#prices" aria-expanded="false" aria-controls="prices">
                          本計劃收費嗎
                        </button>
                      </h2>
                      <div id="prices" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <span style="color:black">
                            <strong>我們這個短網址計劃均為免費</strong> 未來將會繼續推出更多免費服務，協助及幫助更多有需要的「網絡用戶」。
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <a href="https://www.resonancecraft.net/apply.php" target="_blank" class="btn btn-primary">Register an account! 註冊帳號</a>
                </div>
              </div>
            <?php } else { ?>
              <div class="info" style="width: 100%;">
                <div class="card">
                  <div class="card-body">
                    <?php
                    if (count($_SESSION['url_list']) != 0) {
                    ?>
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">短網址</th>
                            <th scope="col">標題</th>
                            <th scope="col">創建時間</th>
                            <th scope="col">@</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $url_list = $_SESSION['url_list'];
                          foreach ($url_list as $key => $value) { ?>
                            <tr>
                              <th scope="row"><a href="https://l.ttfmc.net/<?php echo $key; ?>" target="_blank"><?php echo $key; ?></a></th>
                              <td id="<?php echo $key ?>_title"><?php echo $value['title'] ?></td>
                              <td><?php echo date('d/m/Y H:i', $value['timestamp']); ?></td>
                                <td><a href="backend/pdo/user_actions.php?remove&code=<?php echo $key; ?>"><i class="fa fa-times" style="color:red" title="移除短網址" aria-hidden="true"></i></a></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    <?php } else { ?>
                      <div class="alert alert-dark" role="alert">
                        <h6 style="align-content: center">Let's create some magic url ~ 快來創建短網址吧 !</h6>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
              </div>

          </div>

        </div>
    </section><!-- End Login Section -->
  </main>


  <main id="main">

    <!-- ======= About Us Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>關於我們</h2>
          <p>提供開源以及免費的優質服務，讓更多人能夠接觸 IT 及 Minecraft 伺服器。拓展社群共振效應。</p>
        </div>

        <div class="row mt-2">
          <div class="col-lg-4 col-md-6 icon-box">
            <div class="icon"><i class="bi bi-file-earmark-lock"></i></div>
            <h4 class="title"><a href="">安全存取</a></h4>
            <p class="description">經轉換的短網址均儲存在 HN 受保護的資料庫當中，安全並不會洩漏。</p>
          </div>
          <div class="col-lg-4 col-md-6 icon-box">
            <div class="icon"><i class="bi bi-activity"></i></div>
            <h4 class="title"><a href="">快速響應</a></h4>
            <p class="description">我們摒棄傳統的 RDBMS 儲存方法，轉用 REDIS 高速快取伺服器，確保存取效能。</p>
          </div>
          <div class="col-lg-4 col-md-6 icon-box">
            <div class="icon"><i class="bi bi-kanban"></i></div>
            <h4 class="title"><a href="">管理短網址</a></h4>
            <p class="description">用戶可使用 RCN 帳號 或 Discord 登入，管理由帳號所創建的短網址。</p>
          </div>
        </div>

      </div>
    </section><!-- End About Us Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>ResonanceCraft Network (Member of HN)</span> & <span>Team SHD</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        Web Application designed by <a href="https://org.hypernology.com/">HN</a> ❤️ Discord Bot designed by <a href="https://shdteam.cf/">Team SHD</a>
      </div>
    </div>
  </footer><!-- End #footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>