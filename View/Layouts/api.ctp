<!DOCTYPE html>
<html>
    <head>
        <link href='//fonts.googleapis.com/css?family=Anonymous+Pro' rel='stylesheet' type='text/css'>
        <link href='/api_manager/css/style.css' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="calls">
            <h1><?php echo $title; ?></h1>
            <div class="actions">
                <ul>
                    <?php 
                        echo $this->fetch('menu');
                
                        foreach ($runs as $button => $run) {
                            echo $this->element('ApiManager.run', compact('run', 'button'));
                        }
                    ?>
                    <li>
                        <input type="checkbox" id="showInfo" checked="checked" /> Show extended info
                    </li>
                    <li>
                        <input type="checkbox" id="showData" checked="checked" /> Show data
                    </li>
                </ul>
            </div>
            <?php
                echo $this->fetch('calls');
        
                if (isset($facebook)) {
                    echo $this->element('ApiManager.facebook', compact('facebook'));
                }
        
                foreach ($calls as $call) {
                    echo $this->element('ApiManager.call', compact('call'));
                }
            ?>
        </div>
        <div class="results">
            <h1>Results</h1>
            <div id="result"></div>
        </div>
        <?php echo $this->element('ApiManager.javascript', compact('tokens', 'validation')); ?>
    </body>
</html>