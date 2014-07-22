<?php 

/**
 * Set the next question on the right version
 */
function nextQ(){
  global $versions,
         $questionCount;
  
  if ( ($questionCount / $versions) == s::get('realQuestion')+1){
    // Next Version, Question 1
    s::set('version',s::get('version')+1);
    s::set('question',s::get('question')+1);
    s::set('realQuestion',0);
    
    if (s::get('version')+1 <= $versions){
      s::set('nextVersionScreen',true);  
    }
  }else{
    // Same Version, Next Question
    s::set('question',s::get('question')+1);
    s::set('realQuestion',s::get('realQuestion')+1);
  }
}

function saveQ($clicks=false,$result=false){
  $entry = array(
    'question' => s::get('realQuestion'), 
    'version'  => s::get('version'),
    'result'   => $result,
    'clicks'   => $clicks
    );
  $data = s::get('saved',array());
  $data[] = $entry;
  s::set('saved',$data);
}

function resetQ(){
  s::restart();
  s::set('id',time());
  s::set('version',0);
  s::set('question',0);
  s::set('realQuestion',0);
  s::set('saved',array());
  s::set('pleaseSave',false);
  s::set('nextVersionScreen',true);
}

function version($number){
  $alphas = range('A', 'Z');
  return $alphas[$number];
}