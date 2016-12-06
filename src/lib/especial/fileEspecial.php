<?php

class fileEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($campo, $array, $form){
/*'<audio controls>
  <source src="horse.mp3" type="audio/mpeg">
  <source src="horse.ogg" type="audio/ogg">
  <embed height="50" width="100" src="horse.mp3">
</audio>'
<video width="320" height="240" controls>
  <source src="movie.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  <object data="movie.mp4" width="320" height="240">
    <embed src="movie.swf" width="320" height="240">
  </object> 
</video>
         */;
        $form->file($campo, $array['name'], @$array['description']);
    }	
	
	public function filter($name, $array){
		return;
	}
	
	public function format($dados, &$value){
		return;
	}
}