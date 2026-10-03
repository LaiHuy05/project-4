<?php
/**
 * One place for checkout validation and server-side order totals.
 * All writes within each checkout share the same PDO transaction.
 */
final class CheckoutService
{
    public static function parseItemIds($raw): array
    {
        if (!is_string($raw) || $raw === '') {
            throw new InvalidArgumentException('Chưa chọn sản phẩm trong giỏ hàng.');
        }
        $tokens = explode(',', $raw);
        if (count($tokens) > 100) {
            throw new InvalidArgumentException('Giỏ hàng có quá nhiều mục.');
        }
        $ids = [];
        foreach ($tokens as $token) {
            if (!preg_match('/^[1-9][0-9]*$/D', $token)) {
                throw new InvalidArgumentException('Mã mục giỏ hàng không hợp lệ.');
            }
            $id = filter_var($token, FILTER_VALIDATE_INT);
            if ($id === false || $id < 1 || in_array($id, $ids, true)) {
                throw new InvalidArgumentException('Mục giỏ hàng trùng hoặc không hợp lệ.');
            }
            $ids[] = $id;
        }
        return $ids;
    }

    public static function positiveInt($value, string $label, int $max = PHP_INT_MAX): int
    {
        if (!is_scalar($value) || filter_var($value, FILTER_VALIDATE_INT) === false
            || (int) $value < 1 || (int) $value > $max) {
            throw new InvalidArgumentException($label . ' không hợp lệ.');
        }
        return (int) $value;
    }

    public static function totals(array $rows): array
    {
        $amount = 0;
        $quantity = 0;
        foreach ($rows as $row) {
            $q = self::positiveInt($row['cd_quantity'] ?? null, 'Số lượng', 100);
            if (!isset($row['sp_price']) || !is_numeric($row['sp_price'])
                || (float) $row['sp_price'] < 0) {
                throw new InvalidArgumentException('Giá sản phẩm không hợp lệ.');
            }
            $price = (int) round((float) $row['sp_price']);
            $amount += $price * $q;
            $quantity += $q;
        }
        return ['total' => $amount, 'quantity' => $quantity];
    }

    public static function validateForm(array $form): array
    {
        $fields = [
            'namePay' => 'Tên', 'emailPay' => 'Email', 'phonePay' => 'Số điện thoại',
            'addressPay' => 'Địa chỉ', 'countryPay' => 'Quốc gia', 'cityPay' => 'Thành phố',
            'districtPay' => 'Quận/Huyện', 'communePay' => 'Xã/Phường'
        ];
        $errors = [];
        foreach ($fields as $key => $label) {
            if (!isset($form[$key]) || !is_scalar($form[$key]) || trim((string) $form[$key]) === '') {
                $errors[$key] = $label . ' không được để trống!';
            }
        }
        if (!isset($errors['emailPay']) && !filter_var($form['emailPay'], FILTER_VALIDATE_EMAIL)) {
            $errors['emailPay'] = 'Email không hợp lệ!';
        }
        if (!isset($errors['phonePay']) && !preg_match('/^[0-9]{10,11}$/D', trim((string) $form['phonePay']))) {
            $errors['phonePay'] = 'Số điện thoại không hợp lệ!';
        }
        return $errors;
    }

    private static function writeOrder(int $userId, array $form, array $rows, array $totals): string
    {
        // No total, quantity, role, or account ID comes from POST or GET.
        $code = 'FS_' . random_int(100000, 999999);
        $id = insert_order(
            trim($form['namePay']), trim($form['emailPay']), trim($form['phonePay']),
            trim($form['addressPay']), trim($form['countryPay']), trim($form['cityPay']),
            trim($form['districtPay']), trim($form['communePay']),
            trim((string) ($form['messagePay'] ?? '')), 'chờ xác nhận',
            $totals['total'], $userId, $totals['quantity'], $code
        );
        foreach ($rows as $row) {
            insert_orderdetail(
                $id, $row['id_sp'], $row['cd_quantity'],
                $row['cd_option'], $row['cd_optionColor']
            );
        }
        return (string) $id;
    }

    public static function createCartOrder(int $userId, array $ids, array $form): string
    {
        if (self::validateForm($form)) throw new InvalidArgumentException('Biểu mẫu không hợp lệ.');
        if (!$ids || count($ids) > 100 || count($ids) !== count(array_unique($ids))) {
            throw new InvalidArgumentException('Giỏ hàng không hợp lệ.');
        }
        return pdo_transaction(function () use ($userId, $ids, $form) {
            $rows = load_cart_items_by_ids_for_user($userId, $ids, true);
            if (count($rows) !== count($ids)) {
                throw new InvalidArgumentException('Giỏ hàng không thuộc tài khoản hoặc đã được thanh toán.');
            }
            $summary = self::totals($rows);
            $id = self::writeOrder($userId, $form, $rows, $summary);
            foreach ($ids as $cartDetailId) {
                // Rows were locked and owner-checked by the SELECT above.
                delete_cartdetail($cartDetailId);
            }
            return $id;
        });
    }

    public static function createSingleOrder(int $userId, int $productId, int $quantity,
        string $memory, string $color, array $form): string
    {
        if (self::validateForm($form)) throw new InvalidArgumentException('Biểu mẫu không hợp lệ.');
        $quantity = self::positiveInt($quantity, 'Số lượng', 100);
        return pdo_transaction(function () use ($userId, $productId, $quantity, $memory, $color, $form) {
            $product = pdo_query_one('SELECT sp_id, sp_price FROM product WHERE sp_id = ? FOR UPDATE', $productId);
            if (!$product) throw new InvalidArgumentException('Sản phẩm không tồn tại.');
            $rows = [[
                'id_sp' => $productId, 'cd_quantity' => $quantity,
                'cd_option' => $memory, 'cd_optionColor' => $color,
                'sp_price' => $product['sp_price']
            ]];
            return self::writeOrder($userId, $form, $rows, self::totals($rows));
        });
    }
}
