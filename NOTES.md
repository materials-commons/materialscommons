## Reading Rethinkdb JSON files
$handle = fopen("/tmp/processes.json", "r");
// Read past first [
fgets($handle);
$line = fgets($handle);
$line = trim($line);
$line = substr_replace($line, "", -1);
$data = json_decode($line, true);
$data["uuid"] = $data["id"];
unset($data["id"]);
