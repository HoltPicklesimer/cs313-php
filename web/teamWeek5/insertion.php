<?php

require "dbConnect.php";

if ($_POST)
{
	$book = $_POST["book"];
	$chapter = $_POST["chapter"];
	$verse = $_POST["verse"];
	$content = $_POST["content"];
	$topicId = $_POST["topicId"];
}

$stmt = $db->prepare("INSERT INTO Scriptures (book, chapter, verse, content) VALUES (:book, :chapter, :verse, :content);
	INSERT INTO ScriptureTopics (scripture_id, topic_id) VALUES ((SELECT id FROM Scriptures WHERE book = :book AND chapter = :chapter AND verse = :verse AND content = :content), :topic_id)");
$stmt->bindValue(':book', $book, PDO::PARAM_STR);
$stmt->bindValue(':chapter', $chapter, PDO::PARAM_INT);
$stmt->bindValue(':verse', $verse, PDO::PARAM_INT);
$stmt->bindValue(':content', $content, PDO::PARAM_STR);
$stmt->bindValue(':topic_id', $topicId, PDO::PARAM_INT);
$stmt->execute();

?>

<!DOCTYPE html>
<html>
<head>
	<title>Insertion</title>
</head>
<body>

</body>
</html>