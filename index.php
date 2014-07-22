<?php 
ob_start();
if (!defined('DS'))   define('DS'  , DIRECTORY_SEPARATOR);
if (!defined('ROOT')) define('ROOT', dirname(__FILE__));
if (!defined('DATA')) define('DATA', ROOT . DS . 'data');
if (!defined('HOME')) define ('HOME', __FILE__);

require_once(ROOT . DS . 'lib' . DS .'toolkit.php');
require_once(ROOT . DS . 'config.php');

if (c::get('display.errors',false)){
  @ini_set('display_errors',1);
  error_reporting(-1);
}else{
  error_reporting(0);
}

s::start();

// Load Question File
$questionFile = ROOT.DS.'questions.json';
$questions = f::read($questionFile,'json');

// Count Different Versions
$varsFolder = ROOT.DS.'vars'.DS;
$varsFiles = dir::read($varsFolder);
$versions = count($varsFiles);

// Generate Questions for each version
$allQuestion = array();
for ($i=0; $i < $versions; $i++) { 
  $allQuestion[] = $questions;
}
$questionCount = count($questions) * $versions;

// Initialize if first visit
if (s::get('id',false) === false){
  resetQ();
}
// Functions for actions
require_once(ROOT . DS . 'lib' . DS .'functions.php');

// ACTIONS
if (get('action',false) != false){
  $action = get('action');
  switch ($action) {
    case 'skip':
      // get clicks
      $clicks = get('clicks',false);
      // save no result in data
      saveQ($clicks);
      // set next question
      nextQ();
      //redirect
      go(HOME);
    break;

    case 'next':
      // get clicks and result
      $clicks = get('clicks',false);
      $result = get('result',false);
      // save result in data
      saveQ($clicks,$result);
      // set next question
      nextQ();
      //redirect
      go(HOME);
    break;

    case 'reset':
      resetQ();
      go(HOME);
    break;

    case 'submit':
      s::set('pleaseSave',true);
      s::set('comment',get('comment',false));
      go(HOME);
    break;

    case 'okay':
      s::set('nextVersionScreen',false);
      go(HOME);
    break;
  }
}

?>
<!doctype html>
<html class="no-js">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="robots" content="index,follow">
  
  <title>Tree Testing</title>
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

<h1>Tree Testing</h1>

<?php if (s::get('nextVersionScreen',false)): ?>
  <div class="box screen">
    <h2>Version: <?=version(s::get('version'))?></h2>
    <p>You will get asked <?=count($questions)?> questions for Version <?=version(s::get('version'))?>.</p>
    <p><br></p>
    <p>Try to find the solution/information for the question in the navigation tree
      <br>and select "Found here".
      <br>
      <br>If you can't solve the question, simple click "Skip question".</p>
    <a href="?action=okay" class="btn">Let's start</a>
  </div>
  <?php exit(); ?>
<?php endif; ?>

<?php if (s::get('question',0) < $questionCount ): ?>

  <form action="?action=next" method="POST">
    <input type="hidden" readonly name="clicks" id="way">

    <div class="box">
      <p>Question: <?=s::get('question',0)+1?> / <?=$questionCount?></p>
      <h2><?=$allQuestion[s::get('version',0)][s::get('realQuestion',0)]?></h2>
      <p><small>Version: <?=version(s::get('version'))?></small></p>
      <button type="submit" name="action" value="skip">Skip this Question</button>
    </div>

    <?php
      // Load different versions
      $requestFile = s::get('version',0).'.html';
      if (in_array($requestFile, $varsFiles)){
        include ($varsFolder.DS.$requestFile);
      }else{
        echo 'File '.$requestFile.' not found!';
      }
    ?>

  </form>

<?php else: ?>

  <?php if (!s::get('pleaseSave',false)): ?>

    <div class="box result">
      <h2>Last Step</h2>
      <p>Which version did you enjoy more?<br>
      Any other comments?</p>
      <form action="?action=submit" method="POST" accept-charset="UTF-8">
        <textarea name="comment" id="" cols="30" rows="10" placeholder="I liked version X, because..."></textarea>
        <button type="submit" name="submit" value="submit">Save &amp; Submit Result</button>
      </form>
    </div>

  <?php else: ?>

    <?php 
      // Saved already?
      if (s::get('didsave',false) === false){

        $save = array(
          'id'       => s::get('id'),
          'started'  => date('Y-m-d H:i',s::get('id')),
          'finished' => date('Y-m-d H:i'),
          'data'     => s::get('saved'),
          'comment'  => s::get('comment',false)
        );
        $path = DATA . DS . s::get('id'). '_'.mt_rand().'.json';
        
        // Save to file
        if (is_writable($path) && c::get('deliver.method') === "file"){
          
          $write = f::write($path, $save);
          s::set('didsave',true);

        // Send by mail
        }else if (c::get('deliver.method') === "mail"){

          $header     = 'From: ' .c::get('deliver.email.to'). "\r\n" .
                        'Reply-To: ' .c::get('deliver.email.to'). "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

          $mail = mail(c::get('deliver.email.to'), "Tree Testing - ".s::get('id'), a::json($save), $header);

          if (!$mail){
            echo "Something went wrong - could not save the file and could not email it :(";
          }else{
            s::set('didsave',true);
          }

        // Echo Output
        }else{
          a::show($save);
        }

      }
     ?>

    <div class="box result">
      <h2>Thank you for your time!</h2>
      <a href="?action=reset">Start again?</a>
    </div>
  <?php endif; // pleaseSave?>

<?php endif; // questionCount?>

  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/main.js"></script>
  </body>
</html>
<?php
ob_end_flush();