<html>
    <head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <style>
            * {
                font-family: Tahoma, sans-serif;
                font-size:12px;
            }
            .green {
                color: green;
            }
            .red {
                color: red;
            }
            .actions {
                text-align: right;
            }
            .actions ul {
                list-style-type: none;
            }
            .actions ul li {
                display: inline;
                margin-right: 10px;
            }
            .call {
                border-top: 1px solid gray;
                margin-left: 10px;
                margin-right: 10px;
                padding-top: 10px;
                padding-bottom: 10px;
            }
            .calls {
                width: 50%;
                float: left;
                border-right: 1px solid gray;
                max-height: 100%;
                overflow: scroll;
            }
            .results {
                float:left;
                width:49%;
                max-height: 100%;
                overflow: scroll
            }
            .result {
                margin: 10;
                word-wrap: break-word;
            }
            button {
            /*    width: 200px;*/
            }
            h1 {
                font-size: 15px;
                margin-left: 10px;
            }
            textarea {
                width: 400px;
                height: 200px;
            }
        </style>
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
                            replyData = $.parseJSON(xhr.responseText);
                            $('.result').html(JSON.stringify(replyData, undefined, 2)); 
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
            <button type="button" data-url="<?php echo $call['url']; ?>" id="<?php echo $call['id']; ?>"><?php !empty($call['url-display']) ? echo $call['url-display'] : echo $call['url']; ?></button>
            <?php if !empty($call['data']) : ?>
            <textarea name="text"><?php echo $call['data']; ?></textarea>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="results">
        <h1>Results</h1>
        <pre class="result">
        </pre>
    </body>
</html>