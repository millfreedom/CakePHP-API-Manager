<link href='//fonts.googleapis.com/css?family=Anonymous+Pro' rel='stylesheet' type='text/css'>
<link href='/api_manager/css/style.css' rel='stylesheet' type='text/css'>

<div class="calls">
    <h1><?php echo $title; ?></h1>
    <div class="actions">
        <ul>
            <?php foreach ($runs as $key => $run) : ?>
                <?php echo $this->element('ApiManager.run', ['run' => $run, 'button' => $key]); ?>
            <?php endforeach; ?>
            <li>
                <input type="checkbox" id="showInfo" checked="checked" /> Show extended info
            </li>
            <li>
                <input type="checkbox" id="showData" checked="checked" /> Show data
            </li>
        </ul>
    </div>
    <?php foreach ($calls as $call) : ?>
        <?php echo $this->element('ApiManager.call', ['call' => $call]); ?>
    <?php endforeach; ?>
</div>
<div class="results">
    <h1>Results</h1>
    <div id="result"></div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.js"></script>
<script>
    var tokens = JSON.parse('<?php echo json_encode($tokens); ?>');

    function replaceTokens(str) {
        for (i in tokens) {
            if (tokens[i] != '') {
                str = str.replace(i, tokens[i]);
            }
        }
        
        return str;
    }

    $(document).ready(function() {
        var runArray = [];
        
        $('.run').click(function() {
            runArray = JSON.parse($(this).attr('data-run')).reverse();

            $('div.call').each(function() {
                if($.inArray($(this).attr('data-id'), runArray) == -1) {
                    $(this).hide();
                }
            });
            
            var call = runArray.pop();
            
            $('#showInfo').click();
            $('#showData').click();
            
            $('#'+call).click();
            window.location.hash = '#' + call + '_anchor';
        });
        
        $('#showInfo').click(function() {
            $(".call > p").toggle();
        });

        $('#showData').click(function() {
            $(".call > textarea").toggle();
        });

        $('button').click(function() {
            
            $('button').attr('disabled', 'disabled');
            
            console.log('Calling '+$(this).attr('id'));
            
            var call = $(this).parent();
            var url = replaceTokens($(this).attr('data-url'));
            
            $(this).attr('data-url-current', url);
            
            var ajax = {
                'url': url,
                'type': 'POST',
                'complete': function(xhr, res) { 
                    replyData = JSON.parse(xhr.responseText);
                    
                    $("#result").html(
                        '<pre class="prettyprint linenums"><code class="language-js">' +
                        JSON.stringify(replyData, null, 2) + 
                        '</code></pre>'
                    );
                    
                    var button = $("button[data-url-current='"+this.url+"']");

                    prettyPrint();
                    
                    if (<?php echo $validation; ?>) {
                        button.siblings('h2').append(' <span class="green">✔</span>');

                        if (typeof button.attr('data-tokens') != 'undefined' && button.attr('data-tokens') != '') {
                            var buttonTokens = JSON.parse(button.attr('data-tokens'));
                    
                            for (i in buttonTokens) {
                                tokens[i] = eval('replyData.'+buttonTokens[i]);
                            }
                        }
                        
                        if (runArray.length == 0) {
                            $('button').removeAttr('disabled');
                        } else {
                            var call = runArray.pop();
            
                            $('#'+call).click();
                            window.location.hash = '#' + call + '_anchor';
                        }
                    } else {
                        button.siblings('h2').append(' <span class="red">✘</span>');
                        $('button').removeAttr('disabled');
                    }
                    
                    $(this).removeAttr('data-url-current');
                }
            }

            if (typeof call.find('textarea').val() != 'undefined') {
                ajax.data = JSON.parse(replaceTokens(call.find('textarea').val()));
            }

            $.ajax(ajax);
        });
    });
</script>