<html>
    <head>
        <link href='//fonts.googleapis.com/css?family=Anonymous+Pro' rel='stylesheet' type='text/css'>
        <link href='/api_manager/css/style.css' rel='stylesheet' type='text/css'>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.js"></script>
        <script src="/api_manager/js/site.js"></script>
        <script>
            $(document).ready(function() {
                $('#showInfo').click(function() {
                    $(".call > p").toggle();
                });
    
                $('#showData').click(function() {
                    $(".call > textarea").toggle();
                });
    
                $('button').click(function() {
                    var call = $(this).parent();
                    var param = call.find('input').val() ? call.find('input').val() : '';
                    var url = $(this).attr('data-url');
                    var ajax = {
                        'url': url,
                        'type': 'POST',
                        'complete': function(xhr, res) { 
                            $('#source').html(xhr.responseText);
                            
                            /** Only process JSON if there is some, and it is not the same as before */
                            if($('#source').val() != jsonsh.old_value)
                            {
                                /** Passed out initial tests, go ahead and make it pretty */
                                jsonsh.make_pretty();
                
                                /** Update our old value to the latest and greatest */
                                jsonsh.old_value = $('#source').val();
                            }
                            /** Source is blank now, no need to do anything, so reset interface */
                            else if($('#source').val() == '')
                            {
                                jsonsh.reset_interface();
                            }
                        }            
                    }
        
                    if (typeof call.find('textarea').val() != 'undefined') {
                        ajax.data = JSON.parse(call.find('textarea').val());
                    }
        
                    $.ajax(ajax);
                });
            });
        </script>
    </head>
    <body>
    <div class="calls">
        <h1><?php echo $title; ?></h1>
        <div class="actions">
            <ul>
                <li>
                    <input type="checkbox" id="showInfo" checked="checked" /> Show extended info
                </li>
                <li>
                    <input type="checkbox" id="showData" checked="checked" /> Show data
                </li>
            </ul>
        </div>
        <?php foreach ($calls as $call) : ?>
        <div class="call">
            <h2><?php echo $call['name']; ?></h2> 
            <p><?php echo $call['description']; ?></p>
            <button type="button" data-url="<?php echo $call['url']; ?>" id="<?php echo $call['id']; ?>"><?php echo $call['button']; ?></button>
            <?php if (!empty($call['data'])) : ?>
            <br />
            <textarea name="text"><?php echo json_encode($call['data'], JSON_PRETTY_PRINT); ?></textarea>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="results">
        <h1>Results</h1>
        <textarea style="display: none;" id="source"></textarea>
        <div id="output_wrapper"></div>
    </body>
</html>