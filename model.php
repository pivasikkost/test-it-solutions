<?php

/**
 * Return list of users.
 */
function get_users($conn)
{
    $statement = $conn->query('SELECT * FROM `users`');
    $users = array();
    while ($row = $statement->fetch()) {
        $users[$row['id']] = $row['name'];
    }

    return $users;
}

/**
 * Return transactions balances of given user.
 */
function get_user_transactions_balances($user_id, $conn)
{     
    // SQL-запрос
    $sql= <<<HEREDOC
    SELECT 
        strftime('%Y', t.trdate) AS year,
        strftime('%m', t.trdate) AS month,
        COALESCE(SUM(CASE 
            WHEN ua1.id IS NULL AND ua2.id IS NOT NULL THEN t.amount 
            ELSE 0 
        END), 0) - 
        COALESCE(SUM(CASE 
            WHEN ua1.id IS NOT NULL AND ua2.id IS NULL THEN t.amount 
            ELSE 0 
        END), 0) AS monthly_balance,
        COUNT(t.id) AS transaction_count
    FROM transactions t
    LEFT JOIN user_accounts ua1 ON t.account_from = ua1.id AND ua1.user_id = :user_id
    LEFT JOIN user_accounts ua2 ON t.account_to = ua2.id AND ua2.user_id = :user_id
    WHERE (ua1.id IS NOT NULL OR ua2.id IS NOT NULL)
    GROUP BY year, month
    ORDER BY year, month;

    HEREDOC;

    // Подготовка запроса
    $stmt = $conn->prepare($sql);
    // Привязываем параметр user_id
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    // Выполняем запрос
    $stmt->execute();

    // Получаем результаты
    $results = $stmt->fetchAll();

    return $results;
}