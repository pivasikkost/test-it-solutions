<?php
include_once('db.php');
include_once('model.php');

//header('Content-Type: application/json');

$conn = get_connect();
$user_id = isset($_GET['user'])
    ? (int)$_GET['user']
    : null;
$users = get_users($conn);
$user_name = $users[$user_id] ?? null;

if ($user_id && $user_name) {
    $month_names = [
        '01' => 'January',
        '02' => 'Februarry',
        '03' => 'March'
    ];
    // Get transactions balances
    $transactions = get_user_transactions_balances($user_id, $conn);
    if ($transactions) {
        $html = "<h2>Transactions of $user_name</h2>
            <table>
                <tr><th>Mounth</th><th>Amount</th><th>Count</th></tr>
        ";
        foreach ($transactions as $transaction) {
            $html .= "<tr>
                    <td>" . $month_names[$transaction['month']] . "</td>
                    <td>" . $transaction['monthly_balance'] . "</td>
                    <td>" . $transaction['transaction_count'] . "</td>
                </tr>";
        }
        $html .= "</table>";
    } else {
        $html = "<h2>No transactions of $user_name</h2>";
    }
    echo /*json_encode(/*["data" => ["html" => */$html/*]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS)*/;
} else {
    //echo json_encode(["error" => "User not found"]);
    echo /*json_encode(/*["data" => ["html" => */"User not found"/*]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS)*/;
}
?>