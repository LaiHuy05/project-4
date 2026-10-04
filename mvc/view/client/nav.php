<?php
function printPrice($price)
{
    echo number_format($price, 0, ',', '.');
}

$isLoggedIn = isset($_SESSION['login'], $_SESSION['user_id']);
$userId = $isLoggedIn ? (int) $_SESSION['user_id'] : null;
$username = $isLoggedIn ? (string) $_SESSION['login'] : '';
$isAdmin = $isLoggedIn && (int) ($_SESSION['role_id'] ?? 0) === 1;

if (!$isLoggedIn && isset($_GET['iduser'])) {
    header('Location: ?client=login');
    exit;
}

$homeUrl = $isLoggedIn ? '?act=client&iduser=' . $userId : '?act=client';
$cartUrl = $isLoggedIn ? '?client=cart&iduser=' . $userId : '?client=cart';
$accountUrl = !$isLoggedIn
    ? '?client=login'
    : ($isAdmin ? '?act=admin' : '?client=detailProfile&iduser=' . $userId);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FourSmart</title>
    <link rel="stylesheet" href="view/assets/index.css?v=<?= time() ?>">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Oleo+Script:wght@400;700&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap");
    </style>
</head>

<nav>
    <div class="nav-full">
        <div class="logo">
            <h1 class="logo-style"><a href="<?= $homeUrl ?>">FourSmart</a></h1>
        </div>

        <div class="category-full">
            <div class="category">
                <svg id="icon-category" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26.99 26.99">
                    <defs>
                        <style>
                            .cls-1 {
                                fill: none;
                                stroke: #fff;
                                stroke-linecap: round;
                                stroke-linejoin: round;
                                stroke-width: 1.8px;
                            }
                        </style>
                    </defs>
                    <g id="Layer_2" data-name="Layer 2">
                        <g id="Layer_1-2" data-name="Layer 1">
                            <line x1="7.06" y1="7.52" x2="19.92" y2="7.52" class="cls-1"></line>
                            <line x1="7.06" y1="13.49" x2="19.92" y2="13.49" class="cls-1"></line>
                            <line x1="7.06" y1="19.47" x2="11.95" y2="19.47" class="cls-1"></line>
                            <rect x="0.9" y="0.9" width="25.19" height="25.19" rx="4.71" class="cls-1"></rect>
                        </g>
                    </g>
                </svg>

                <a class="category-link" href="#">Danh mục</a>
                <ul class="sub-menu">
                    <?php foreach ($list_category as $category): ?>
                        <?php
                        $categoryUrl = '?client=categoryShow&iddm=' . (int) $category['dm_id'];
                        if ($isLoggedIn) {
                            $categoryUrl .= '&iduser=' . $userId;
                        }
                        ?>
                        <li>
                            <a href="<?= $categoryUrl ?>">
                                <?= htmlspecialchars($category['dm_name'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="search-index">
            <form class="form-index" action="" method="GET">
                <input type="hidden" name="client" value="search">
                <?php if ($isLoggedIn): ?>
                    <input type="hidden" name="iduser" value="<?= $userId ?>">
                <?php endif; ?>
                <input type="text" name="search" id="search" placeholder="Tìm kiếm">
                <button type="submit" class="btn-form-index">
                    <i id="icon-search" class="bx bx-search"></i>
                </button>
            </form>
        </div>

        <div class="hotline-full">
            <div class="hotline">
                <i id="icon-hotline" class="bx bxs-phone"></i>
                <a class="hotline-link" href="tel:0886563826">liên hệ 0886563826</a>
            </div>
        </div>

        <div class="cart-full">
            <a href="<?= $cartUrl ?>">
                <div class="cart">
                    <i id="icon-cart" class="bx bx-cart"></i>
                    <span class="cart-link">Giỏ hàng</span>
                </div>
            </a>
        </div>

        <div class="login-full">
            <a href="<?= $accountUrl ?>">
                <div class="login">
                    <i id="icon-login" class="bx bx-user-circle"></i>
                    <span class="login-link">
                        <?= $isLoggedIn ? htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : 'Đăng Nhập' ?>
                    </span>
                </div>
            </a>
        </div>

        <?php if ($isLoggedIn): ?>
            <div class="login-full">
                <a href="?act=logout">
                    <div class="login">
                        <i id="icon-login" class="bx bx-log-out"></i>
                        <span class="login-link">Đăng Xuất</span>
                    </div>
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>
