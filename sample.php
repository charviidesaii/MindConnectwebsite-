<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>MindConnect - Mood Tracker</h1>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the MOOD_ENTRIES table exists. */
  VerifyMoodEntriesTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the MOOD_ENTRIES table. */
  $mood = htmlentities($_POST['MOOD']);
  $intensity = htmlentities($_POST['INTENSITY']);

  if (strlen($mood) || strlen($intensity)) {
    AddMoodEntry($connection, $mood, $intensity);
  }
?>

<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>MOOD</td>
      <td>INTENSITY</td>
    </tr>
    <tr>
      <td>
        <select name="MOOD" required>
          <option value="happy">Happy</option>
          <option value="sad">Sad</option>
          <option value="anxious">Anxious</option>
          <option value="stressed">Stressed</option>
          <option value="neutral">Neutral</option>
        </select>
      </td>
      <td>
        <input type="number" name="INTENSITY" min="1" max="10" required />
      </td>
      <td>
        <input type="submit" value="Track Mood" />
      </td>
    </tr>
  </table>
</form>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>MOOD</td>
    <td>INTENSITY</td>
    <td>TIMESTAMP</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM MOOD_ENTRIES ORDER BY TIMESTAMP DESC");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>",
       "<td>",$query_data[3], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add a mood entry to the table. */
function AddMoodEntry($connection, $mood, $intensity) {
   $m = mysqli_real_escape_string($connection, $mood);
   $i = mysqli_real_escape_string($connection, $intensity);

   $query = "INSERT INTO MOOD_ENTRIES (MOOD, INTENSITY, TIMESTAMP) VALUES ('$m', '$i', NOW());";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding mood entry data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyMoodEntriesTable($connection, $dbName) {
  if(!TableExists("MOOD_ENTRIES", $connection, $dbName))
  {
     $query = "CREATE TABLE MOOD_ENTRIES (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         MOOD VARCHAR(45),
         INTENSITY INT(2),
         TIMESTAMP DATETIME
       )";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>

