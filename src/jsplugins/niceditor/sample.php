<?php 
    $this->Js->LoadPlugin("niceditor", "editor", "neditor");
    
    echo "<h3>Aplicando a elementos passados por parametro, neste caso uma div</h3>";
    $this->Js->neditor->drawEditable($camp = "teste", $texto = "123...");
    
    echo "<h3>Aplicando em dois text areas</h3>";
    $this->Js->neditor->drawTextArea("area1");
    $this->Js->neditor->drawTextArea("area2");
    
?>

<h3>Como usar</h3>
<code>
    <?php 
        echo '
            $this->Js->LoadPlugin("niceditor", "editor", "neditor");<br/>
            $this->Js->neditor->drawEditable($camp, $texto);<br/>
            $this->Js->neditor->drawTextArea("area1");';
    ?>
</code>