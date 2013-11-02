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
                
                        foreach ($runs as $key => $run) {
                            echo $this->element('ApiManager.run', ['run' => $run, 'button' => $key]);
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
                    echo $this->element('ApiManager.facebook', ['facebook' => $facebook]);
                }
        
                foreach ($calls as $call) {
                    echo $this->element('ApiManager.call', ['call' => $call]);
                }
            ?>
        </div>
        <div class="results">
            <h1>Results</h1>
            <div id="result"></div>
        </div>
        <?php echo $this->element('ApiManager.javascript'); ?>
    </body>
</html>