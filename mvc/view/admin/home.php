<?php
include 'nav.php';
?>
<article>
    <div class="statistics-container">
        <!-- Doanh Thu -->
        <div style="border: none;" class="stat-box green">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number"><?= number_format($profit, 0, ',', '.') ?>đ</span>
                    <h3 class="stat-number">Doanh Thu</h3>
                </div>
            </div>
            <div class="stat-chart">
                <svg height="50" width="100%">
                    <polyline
                        points="0,40 20,30 40,35 60,20 80,25 100,15 120,30 140,40 160,25 180,35 200,25 220,30 240,40 260,35 280,20 300,25 320,30"
                        fill="none" stroke="#ffffff" stroke-width="2" />
                </svg>
            </div>
        </div>

        <!-- Tổng Đơn Hàng -->
        <div style="border: none;" class="stat-box orange">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number"><?= $orderCount ?></span>
                    <h3 class="stat-number">Đơn Hàng</h3>
                </div>
            </div>
            <div class="stat-chart">
                <svg height="50" width="100%">
                    <polyline
                        points="0,40 20,45 40,35 60,30 80,40 100,30 120,15 140,10 160,25 180,30 200,15 220,20 240,30 260,20 280,40 300,35"
                        fill="none" stroke="#ffffff" stroke-width="2" />
                </svg>
            </div>
        </div>

        <!-- Tổng Tài Khoản -->
        <div style="border: none;" class="stat-box purple">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number"><?= $accountCount ?></span>
                    <h3 class="stat-number">Tài Khoản</h3>
                </div>
            </div>
            <div class="stat-chart">
                <svg height="50" width="100%">
                    <polyline
                        points="0,35 20,40 40,45 60,35 80,40 100,30 120,25 140,30 160,20 180,25 200,30 220,40 240,35 260,30 280,25 300,20"
                        fill="none" stroke="#ffffff" stroke-width="2" />
                </svg>
            </div>
        </div>

        <!-- Danh Mục -->
        <a href="" style="text-decoration: none;" class="stat-box blue">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number"><?= $categoryCount ?></span>
                    <h3 class="stat-number">Danh Mục</h3>
                </div>
            </div>
            <div class="stat-chart">
                <svg height="50" width="100%">
                    <polyline
                        points="0,30 20,35 40,40 60,30 80,25 100,35 120,40 140,30 160,20 180,25 200,30 220,35 240,40 260,30 280,20 300,25"
                        fill="none" stroke="#ffffff" stroke-width="2" />
                </svg>
            </div>
        </a>

        <!----------------------------------------------------------------------------->

        <!-- Sản Phẩm -->
        <div style="border: none;" class="stat-box red">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number"><?= $productCount ?></span>
                    <h3 class="stat-number">Sản Phẩm</h3>
                </div>
            </div>
            <div class="stat-chart">
                <svg height="50" width="100%">
                    <polyline
                        points="0,20 20,30 40,35 60,40 80,30 100,25 120,35 140,30 160,40 180,25 200,30 220,20 240,35 260,30 280,40 300,35"
                        fill="none" stroke="#ffffff" stroke-width="2" />
                </svg>
            </div>
        </div>
    </div>
    <br>

    <!-- -------------------------------------------------------------------------------------->
    <div class="main">
        <h1>Biểu đồ doanh thu theo tháng</h1>
        <div class="chart">
            <div class="y-axis">
                <div class="tick">0</div>
                <div class="tick">100Tr</div>
                <div class="tick">200Tr</div>
                <div class="tick">300Tr</div>
                <div class="tick">400Tr</div>
                <div class="tick">500Tr</div>
                <div class="tick">600Tr</div>
                <div class="tick">700Tr</div>
                <div class="tick">800Tr</div>
                <div class="tick">900Tr</div>
                <div class="tick">1T</div>
            </div>
            <div class="bars" id="bars"></div>
        </div>
    </div>

    <script>
        const monthlyRevenue = <?= json_encode(array_values($monthlyRevenue), JSON_NUMERIC_CHECK) ?>;
        const barsContainer = document.getElementById('bars');
        const maxRevenue = 1000000000;

        monthlyRevenue.forEach((revenue, index) => {
            const bar = document.createElement('div');
            bar.className = 'bar';
            bar.style.height = `${(revenue / maxRevenue) * 100}%`;

            const label = document.createElement('span');
            label.textContent = `${(revenue / 1000000).toFixed(0)}M`;
            bar.appendChild(label);

            const monthLabel = document.createElement('label');
            monthLabel.textContent = `Tháng ${index + 1}`;
            bar.appendChild(monthLabel);

            barsContainer.appendChild(bar);
        });
    </script>
    <!-- -------------------------------------------------------------------------------------->

    <br>
    <div class="container-add">
        <br>
        <!-- Bảng thống kê sản phẩm bán chạy -->
        <div class="table-container">
            <h2>Sản Phẩm Bán Chạy</h2>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID Sản Phẩm</th>
                        <th>Mã Sản Phẩm</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Số lượng bán</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_selling as $index =>  $value) {
                        extract($value); ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $sp_ma ?></td>
                            <td><?= $ten_sp ?></td>
                            <td><?= $so_luong_ban ?></td>
                            <td><?= number_format($doanh_thu, 0, ',', '.') ?> VNĐ</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <br>
    </div>
</article>