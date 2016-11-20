<?php
$db = new SQLite3("db/gallicalol.db");
$request = $db->prepare('INSERT INTO memes (gallica_url, top_text, bottom_text, image, scale) VALUES (:url, :top, :bottom, :image, :scale);');
$request->bindValue( ':url', $_POST['query'] );
$request->bindValue( ':top', $_POST['top_text'] );
$request->bindValue( ':bottom', $_POST['bottom_text'] );
$request->bindValue( ':image', $_POST['download_hidden'] );
$request->bindValue( ':scale', $_POST['scale'] );
$result = $request->execute();
if ($result) {
    $last_id = $db->lastInsertRowID();
    header('Location: view.php?id=' . $last_id);  
} else {
    echo "KO";
}

#$filteredData = explode(',', $rawData);
#$unencoded = base64_decode($filteredData[1]);
?>