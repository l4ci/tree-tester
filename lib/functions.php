<?php 

/**
 * Set the next question on the right version
 */
function nextQ(){
  global $versions;
  if (s::get('version',0) < $versions-1){
    // set next version on same question
    s::set('version',s::get('version')+1);
    s::set('question',s::get('question')+1);
    s::set('realQuestion',s::get('realQuestion'));
  }else{
    // set first version on next question
    s::set('version',0);
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
}