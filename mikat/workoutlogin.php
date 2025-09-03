<?php
session_start();

if(!isset($_SESSION['exercises'])) {
    $_SESSION['exercises'] = [];
}


if(empty($_SESSION['exercises']) && isset($_COOKIE['exercises'])) {
    $cookieData = $_COOKIE['exercises'];
    if($cookieData) {
        $exList = explode(";", $cookieData);
        foreach($exList as $ex) {
            if(trim($ex) === "") continue;
            list($name, $sets, $reps, $weight, $notes) = explode("|", $ex);
            $_SESSION['exercises'][] = [
                'name' => $name,
                'sets' => $sets,
                'reps' => $reps,
                'weight' => $weight,
                'notes' => $notes
            ];
        }
    }
}

$errors = [];
$success = false;
$sessionMessage = '';
$currentScreen = 'loggerScreen'; 

$exerciseName = '';
$sets = 1;
$reps = 1;
$weight = 0;
$notes = '';


function saveExercisesCookie($exercises) {
    $data = [];
    foreach($exercises as $ex) {
        $data[] = implode("|", [$ex['name'], $ex['sets'], $ex['reps'], $ex['weight'], $ex['notes']]);
    }
    setcookie('exercises', implode(";", $data), time() + 3600, "/"); // 1 hour
}


if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['addExercise'])) {
        $exerciseName = trim($_POST['exerciseName'] ?? '');
        $sets = trim($_POST['sets'] ?? 1);
        $reps = trim($_POST['reps'] ?? 1);
        $weight = trim($_POST['weight'] ?? 0);
        $notes = trim($_POST['notes'] ?? '');

       
        if($exerciseName === '') $errors['exerciseName'] = "Exercise name required!";
        if(!is_numeric($sets) || $sets < 1) $errors['sets'] = "Sets must be 1 or more!";
        if(!is_numeric($reps) || $reps < 1) $errors['reps'] = "Reps must be 1 or more!";
        if(!is_numeric($weight) || $weight < 0) $errors['weight'] = "Weight cannot be negative!";

        if(empty($errors)) {
            $_SESSION['exercises'][] = [
                'name' => $exerciseName,
                'sets' => $sets,
                'reps' => $reps,
                'weight' => $weight,
                'notes' => $notes
            ];
            saveExercisesCookie($_SESSION['exercises']);
            $success = "Exercise added!";
            
            $exerciseName = '';
            $sets = 1;
            $reps = 1;
            $weight = 0;
            $notes = '';
        }

        $currentScreen = 'loggerScreen'; 

    } elseif(isset($_POST['saveSession'])) {
        if(empty($_SESSION['exercises'])) {
            $sessionMessage = ['error' => "No exercises logged"];
        } else {
            if(!isset($_SESSION['savedSessions'])) $_SESSION['savedSessions'] = [];
            $_SESSION['savedSessions'][] = $_SESSION['exercises'];
            $_SESSION['exercises'] = [];
            setcookie('exercises', '', time() - 3600, "/"); // delete cookie
            $sessionMessage = ['ok' => "Session saved!"];
        }

        $currentScreen = 'summaryScreen'; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Workout Logger</title>
<style>
body { font-family: Arial, sans-serif; margin:0; display:flex; height:100vh; background:#e0f7fa; }
.sidebar { width:180px; background:#00796b; color:white; padding-top:20px; }
.sidebar button { display:block; width:90%; margin:10px auto; padding:12px; background:#00796b; border:none; color:white; cursor:pointer; border-radius:5px; text-align:left;}
.sidebar button:hover { background:#004d40; }
.main { flex:1; padding:20px; overflow-y:auto; }
.screen { display:none; background:#b2dfdb; padding:20px; border-radius:10px; max-width:700px; margin:auto; }
input, textarea, button, label { display:block; width:100%; margin:10px 0; padding:10px; border-radius:5px; border:1px solid #004d40; }
label { margin-top:12px; font-weight:bold; background:none; border:none; padding:0; }
table { width:100%; border-collapse:collapse; margin-top:15px; background:white; }
th, td { border:1px solid #004d40; padding:8px; text-align:center; }
th { background:#004d40; color:white; }
#timeDisplay { font-size:2em; text-align:center; margin:20px 0; font-family:monospace; }
.error { color:red; font-size:14px; margin:3px 0 10px 0; }
.ok { color:green; font-size:14px; margin:3px 0 10px 0; }
</style>
</head>
<body>
<div class="sidebar">
  <button onclick="showScreen('timerScreen')">Workout Timer</button>
  <button onclick="showScreen('loggerScreen')">Exercise Logger</button>
  <button onclick="showScreen('summaryScreen')">Session Summary</button>
</div>

<div class="main">
  <h2 style="text-align:center;">Workout Logger</h2>

  <div id="timerScreen" class="screen">
    <h3 style="text-align:center;">Workout Timer</h3>
    <p id="timeDisplay">00:00:00</p>
    <button onclick="startTimer()">Start</button>
    <button onclick="stopTimer()">Stop</button>
    <button onclick="resetTimer()">Reset</button>
  </div>

  <div id="loggerScreen" class="screen">
    <h3 style="text-align:center;">Exercise Logger</h3>

    <?php if($success) echo "<p class='ok'>$success</p>"; ?>

    <form method="post" action="">
      <label for="exerciseName">Exercise Name</label>
      <input type="text" name="exerciseName" value="<?= htmlspecialchars($exerciseName) ?>">
      <p class="error"><?= $errors['exerciseName'] ?? '' ?></p>

      <label for="sets">Sets</label>
      <input type="number" name="sets" value="<?= htmlspecialchars($sets) ?>">
      <p class="error"><?= $errors['sets'] ?? '' ?></p>

      <label for="reps">Reps</label>
      <input type="number" name="reps" value="<?= htmlspecialchars($reps) ?>">
      <p class="error"><?= $errors['reps'] ?? '' ?></p>

      <label for="weight">Weight (kg)</label>
      <input type="number" name="weight" value="<?= htmlspecialchars($weight) ?>">
      <p class="error"><?= $errors['weight'] ?? '' ?></p>

      <label for="notes">Notes</label>
      <textarea name="notes"><?= htmlspecialchars($notes) ?></textarea>

      <button type="submit" name="addExercise">Add Exercise</button>
    </form>

    <table>
      <thead>
        <tr><th>Exercise</th><th>Sets</th><th>Reps</th><th>Weight</th><th>Notes</th></tr>
      </thead>
      <tbody>
        <?php foreach($_SESSION['exercises'] as $ex): ?>
        <tr>
          <td><?= htmlspecialchars($ex['name']) ?></td>
          <td><?= htmlspecialchars($ex['sets']) ?></td>
          <td><?= htmlspecialchars($ex['reps']) ?></td>
          <td><?= htmlspecialchars($ex['weight']) ?></td>
          <td><?= htmlspecialchars($ex['notes']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div id="summaryScreen" class="screen">
    <h3 style="text-align:center;">Session Summary</h3>
    <?php if(isset($sessionMessage['error'])) echo "<p class='error'>{$sessionMessage['error']}</p>"; ?>
    <?php if(isset($sessionMessage['ok'])) echo "<p class='ok'>{$sessionMessage['ok']}</p>"; ?>
    <form method="post" action="">
      <button type="submit" name="saveSession">Save Session</button>
    </form>

    <div id="savedSessions">
      <?php if(!empty($_SESSION['savedSessions'])): ?>
        <?php foreach($_SESSION['savedSessions'] as $session): ?>
          <div class="saved-session">
            <h4>Session (<?= date("Y-m-d H:i:s") ?>)</h4>
            <table>
              <thead>
                <tr><th>Exercise</th><th>Sets</th><th>Reps</th><th>Weight</th><th>Notes</th></tr>
              </thead>
              <tbody>
                <?php foreach($session as $ex): ?>
                  <tr>
                    <td><?= htmlspecialchars($ex['name']) ?></td>
                    <td><?= htmlspecialchars($ex['sets']) ?></td>
                    <td><?= htmlspecialchars($ex['reps']) ?></td>
                    <td><?= htmlspecialchars($ex['weight']) ?></td>
                    <td><?= htmlspecialchars($ex['notes']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
let timer, seconds=0;
function startTimer() {
  stopTimer();
  timer = setInterval(()=>{
    seconds++;
    let h=String(Math.floor(seconds/3600)).padStart(2,"0"),
        m=String(Math.floor((seconds%3600)/60)).padStart(2,"0"),
        s=String(seconds%60).padStart(2,"0");
    document.getElementById("timeDisplay").textContent=h+":"+m+":"+s;
  },1000);
}
function stopTimer(){ clearInterval(timer); }
function resetTimer(){ stopTimer(); seconds=0; document.getElementById("timeDisplay").textContent="00:00:00"; }

function showScreen(id){
  ["timerScreen","loggerScreen","summaryScreen"].forEach(s => document.getElementById(s).style.display="none");
  document.getElementById(id).style.display="block";
}

showScreen("<?= $currentScreen ?>");
</script>
</body>
</html>
