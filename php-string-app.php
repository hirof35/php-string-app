<?php
// 初期化
$text = '';
$results = [];

// ボタンが押された（POST送信された）ときの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
    $text = $_POST['text'];

    // テキストを行ごとに分割
    $lines = preg_split('/\r\n|\r|\n/', $text);

    foreach ($lines as $index => $line) {
        $lineNumber = $index + 1;
        
        // 判定用にアルファベットだけを取り出す
        $justLetters = preg_replace('/[^a-zA-Z]/', '', $line);

        // 各行の結果を配列に格納
        if ($justLetters === '') {
            $results[] = "{$lineNumber}行目: アルファベットが含まれていません（空白・数字・記号など）";
        } elseif (ctype_upper($justLetters)) {
            $results[] = "{$lineNumber}行目: すべて【大文字】です。 (内容: \"" . htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . "\")";
        } elseif (ctype_lower($justLetters)) {
            $results[] = "{$lineNumber}行目: すべて【小文字】です。 (内容: \"" . htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . "\")";
        } else {
            $results[] = "{$lineNumber}行目: 【大文字と小文字が混ざって】います。 (内容: \"" . htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . "\")";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>大文字・小文字 行ごと判定ツール</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 30px auto; padding: 0 20px; line-height: 1.6; }
        textarea { width: 100%; height: 150px; padding: 10px; font-size: 16px; box-sizing: border-box; }
        button { margin-top: 10px; padding: 10px 20px; font-size: 16px; background-color: #007bff; color: white; border: none; cursor: pointer; border-radius: 4px; }
        button:hover { background-color: #0056b3; }
        .result-box { margin-top: 30px; padding: 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; }
        .result-item { margin-bottom: 5px; font-family: monospace; }
    </style>
</head>
<body>

    <h2>大文字・小文字 行ごと判定ツール</h2>
    <p>テキストエリアに文字を入力して「判定する」ボタンを押してください。</p>

    <form action="" method="POST">
        <!-- 入力した文字が消えないように、前回入力値をechoしています -->
        <textarea name="text" placeholder="ここにテキストを入力してください&#10;例:&#10;HELLO&#10;world&#10;Php"><?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?></textarea>
        <button type="submit">判定する</button>
    </form>

    <!-- 結果がある場合のみ表示 -->
    <?php if (!empty($results)): ?>
        <div class="result-box">
            <h3>◆ 判定結果</h3>
            <?php foreach ($results as $result): ?>
                <div class="result-item"><?php echo $result; ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</body>
</html>
