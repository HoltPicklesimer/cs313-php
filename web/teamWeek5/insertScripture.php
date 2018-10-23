<?php

require "dbConnect.php";

$stmt = $db->prepare('SELECT id, name FROM Topics');
$stmt->execute();
$topicList = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Insert Scripture</title>
</head>
<body>

	<form method="post" action="insertScripture.php">
		<input type="text" name="book"><br/>
		<input type="text" name="chapter"><br/>
		<input type="text" name="verse"><br/>
		<input type="text" name="content"><br/>
		
<?php foreach($topicList as $topic) {
			$topicName = $topic["name"];
			$topicId = $topic["id"]; ?>
			<input type="checkbox" name="topicId" value="<?php echo $topicId; ?>"><?php echo $topicName; ?><br/>
<?php } ?>
		<button type="submit" name="submitted">Add Scripture</button>
	</form>

</body>
</html>